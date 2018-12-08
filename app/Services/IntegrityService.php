<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 7/9/2018
 * Time: 10:52 AM
 */

namespace App\Services;


use App\Contracts\DefinitionRepository;
use App\Contracts\SettingRepository;
use App\Contracts\ShopRepository;
use App\Models\Shop;
use App\Traits\ShopifyTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use stdClass;

class IntegrityService
{
    use ShopifyTrait;

    /**
     * @var ShopRepository
     */
    protected $shopRepo;

    /**
     * @var SettingRepository
     */
    protected $settingRepo;

    /**
     * @var DefinitionRepository
     */
    protected $definitionRepo;

    /**
     * @var BillingService
     */
    protected $billingService;

    /**
     * IntegrityService constructor.
     * @param ShopRepository $shopRepo
     * @param SettingRepository $settingRepo
     * @param DefinitionRepository $definitionRepo
     * @param BillingService $billingService
     */
    public function __construct(ShopRepository $shopRepo,
                                SettingRepository $settingRepo,
                                DefinitionRepository $definitionRepo,
                                BillingService $billingService)
    {
        $this->shopRepo = $shopRepo;
        $this->settingRepo = $settingRepo;
        $this->definitionRepo = $definitionRepo;
        $this->billingService = $billingService;
    }

    /**
     * @param $shop
     * @param $accessToken
     * @param stdClass $shopInfo
     * @return mixed
     * @throws \Exception
     */
    public function register($shop, $accessToken, stdClass $shopInfo)
    {
        // ----- delete if already exists
        $shopObj = $this->shopRepo->findByField('name', $shop);
        if ($shopObj->count() > 0) {
            $shopObj = $shopObj->first();
            $shopObj->delete();
        }

        /* @var Shop $newShop */
        $newShop = $this->shopRepo->create([
            'name'          => $shop,
            'access_token'  => $accessToken,
            'shop_name'     => $shopInfo->name,
            'email'         => $shopInfo->email,
            'owner'         => $shopInfo->shop_owner,
            'plan'          => $shopInfo->plan_name
        ]);

        $freePass = $newShop->shouldGetFreePass();
        $newShop['free_pass'] = $freePass;
        $newShop['last_checked'] = $freePass == true ? Carbon::now() : null;
        $newShop->save();

        $this->injectHooks($newShop);
        $this->seedDefaults($newShop);

        return $newShop;
    }

    /**
     * Check if the integrity is in place
     * @param $shop
     * @return array|bool
     * @throws \Exception
     */
    public function check($shop)
    {
        $shop = $this->shopRepo->findByField('name', $shop)->first();

        try {
            $theme = $this->getActiveThemeFile($shop);
            $themeFile = isset($theme->asset) ? $theme->asset->value : null;
            return [
                'integrity' => $themeFile == null ? false :
                    (strpos($themeFile, '{% include "'.env('APP_NAME').'" %}') == false ? false : true)
            ];
        } catch (Exception $e) {
            return [ 'integrity' => null ];
        }
    }

    /**
     * inject app hook into active theme
     * @param $shop
     * @return array
     * @throws \Exception
     */
    public function fix($shop)
    {
        $shop = $this->shopRepo->findByField('name', $shop)->first();
        $theme = $this->getActiveThemeFile($shop);

        $shopify = $this->getShopifyObj($shop);
        try {
            // ----- inject theme snippet
            $hookSnippet = '<!--1DEA App Hook start-->'.PHP_EOL.
                        '<link rel="stylesheet" type="text/css" href="'.route('pull.css', ['shop' => $shop->name]).'">'.PHP_EOL.
                        '<script type="text/javascript" src="'.route('pull.js', ['shop' => $shop->name]).'"></script>'.PHP_EOL.
                        '<!--1DEA App Hook end-->';

            $resp = $shopify->call([
                'METHOD'    => 'PUT',
                'URL'       => '/admin/themes/' . $theme->asset->theme_id . '/assets.json',
                'DATA'      => [
                    'asset' => [
                        'key'   => 'snippets/'.env('APP_NAME').'.liquid',
                        'value' => $hookSnippet,
                    ]
                ]
            ]);

            // ----- inject hook placeholder
            $content = str_replace(
                '</title>',
                '</title>'.PHP_EOL.PHP_EOL.'  '.'{% include "'.env('APP_NAME').'" %}'.PHP_EOL,
                $theme->asset->value
            );

            $resp = $shopify->call([
                'METHOD'    => 'PUT',
                'URL'       => '/admin/themes/' . $theme->asset->theme_id . '/assets.json',
                'DATA'      => [
                    'asset' => [
                        'key'   => 'layout/theme.liquid',
                        'value' => $content,
                    ]
                ]
            ]);

            return [ 'status' => true ];

        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * initialize hooks on shop when new install
     * @param $shop
     * @throws \Exception
     */
    public function injectHooks(Shop $shop)
    {
        // ---- inject hooks
        $this->registerUninstallHook($shop);
        $this->registerScriptHook($shop);
    }

    /**
     * @param $shop
     * @return array
     * @throws \Exception
     */
    public function registerUninstallHook($shop)
    {
        $shop = $shop instanceof Shop ? $shop : $this->shopRepo->findByField('name', $shop)->first();
        $response = [
            'status' => '',  // ----- can be ['failure', 'success', 'abort']
            'message' => '',
            'data' => []
        ];

        // ----- register uninstall hook
        $shopify = $this->getShopifyObj($shop);
        $uninstallHook = $shopify->call([
            'URL' => '/admin/webhooks/count.json?topic=app/uninstalled',
            'METHOD' => 'GET'
        ]);

        if (isset($uninstallHook->count) && $uninstallHook->count == 0) {
            try {
                $resp = $shopify->call([
                    'METHOD'    => 'POST',
                    'URL'       => '/admin/webhooks.json',
                    'DATA'      => [
                        'webhook' => [
                            'topic'     => 'app/uninstalled',
                            'address'   => route('hooks.uninstall'),
                            'format'    => 'json'
                        ]
                    ]
                ]);

                $response['status'] = 'success';
                $response['message'] = 'Hook injected successfully.';
                $response['data'] = $resp->webhook;

            } catch (\Exception $e) {
                $response['status'] = 'failure';
                $response['message'] = $e->getMessage();
            }
        } else {
            $response['abort'] = 'failure';
            $response['message'] = 'Hook already exists.';
        }

        return $response;
    }

    public function registerScriptHook($shop)
    {
        $shop = $shop instanceof Shop ? $shop : $this->shopRepo->findByField('name', $shop)->first();
        $shopify = $this->getShopifyObj($shop);
        $resp = $shopify->call([
            'METHOD'    => 'POST',
            'URL'       => '/admin/script_tags.json',
            'DATA'      => [
                'script_tag' => [
                    'event' => 'onload',
                    'src'   => route('lazy.js', ['shop' => $shop->name]),
                ]
            ]
        ]);
    }

    /**
     * get active theme's main template file
     * @param $shop
     * @return null
     * @throws \Exception
     */
    private function getActiveThemeFile($shop)
    {
        $shopify = $this->getShopifyObj($shop);
        $resp = $shopify->call(['URL' => '/admin/themes.json', 'METHOD' => 'GET']);
        $activeTheme = null;
        $themeFile = null;

        foreach($resp->themes as $key => $theme){
            if($theme->role == "main"){
                $activeTheme = $theme->id;
            }
        }

        if($activeTheme) {
            $themeFile = $shopify->call([
                'URL' => '/admin/themes/' . $activeTheme . '/assets.json?asset[key]=layout/theme.liquid',
                'METHOD' => 'GET'
            ]);
        }

        return $themeFile;
    }

    private function seedDefaults(Shop $shop)
    {
        // ----- add settings
        $settings = $this->settingRepo->create(array_merge(config('defaults.settings'), ['shop_id' => $shop->id]));
        $settings->save();

        // ----- add definitions
        $definition = $this->definitionRepo->create(array_merge(config('defaults.definitions'), ['setting_id' => $settings->id]));
        $definition->save();
    }

    public function uninstall($shop)
    {
        try {
            $shop = $this->shopRepo->findByField('name', $shop)->first();
            if ($shop->billing != null) {
                $this->billingService->setShop($shop)->optout();
            }
            $shop->delete();

            return response([
                'status' => true
            ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => false
            ], 200);
        }
    }

    public function eraseCustomers(Request $request)
    {
        return response('', 200);
    }

    public function eraseShop(Request $request)
    {
        return response('', 200);
    }

    public function acceptBillingStatus(Request $request)
    {
        $shop = $request->session()->has('shop') ? $request->session()->get('shop') : null;
        $shop = $this->shopRepo->findByField('name', $shop)->first();

        $data = [
            'status'   => 'pending',
            'link'      => '',
            'apps'      => $shop != null ? 'https://'.$shop->name.'/admin/apps' : ''
        ];

        if ($shop != null && $shop->billing != null && $shop->billing->status == 'pending')
        {
            $shopify = $this->getShopifyObj($shop);
            $charge = $shopify->call(['URL' => 'admin/recurring_application_charges/'.$shop->billing->shopify_billing_id.'.json', 'METHOD' => 'GET']);
            $chargeDetails = $charge->recurring_application_charge;
            $data['link'] = $chargeDetails->confirmation_url;
        }
        elseif ($shop != null && $shop->billing != null && $shop->billing->status == 'declined')
        {
            $data['status'] = 'declined';
            $data['link'] = route('pages.restartBilling');
        }
        return $data;
    }

    public function cleanUninstall(Request $request)
    {
        $shop = $request->session()->get('shop');
        $shop = $this->shopRepo->findByField('name', $shop)->first();
        $shopify = $this->getShopifyObj($shop);

        $theme = $this->getActiveThemeFile($shop);
        $themeFile = isset($theme->asset) ? $theme->asset->value : null;
        $gotHook = $themeFile == null ? false :
            (strpos($themeFile, '{% include "'.env('APP_NAME').'" %}') == false ? false : true);

        try {
            // ---- remove hook placeholder
            if ($gotHook) {
                $content = str_replace(
                    '{% include "'.env('APP_NAME').'" %}',
                    '',
                    $theme->asset->value
                );

                $resp = $shopify->call([
                    'METHOD'    => 'PUT',
                    'URL'       => '/admin/themes/' . $theme->asset->theme_id . '/assets.json',
                    'DATA'      => [
                        'asset' => [
                            'key'   => 'layout/theme.liquid',
                            'value' => $content,
                        ]
                    ]
                ]);
            }

            // ----- send a delete call for the hook asset itself
            $hookFile = $shopify->call([
                'URL' => '/admin/themes/' . $theme->asset->theme_id . '/assets.json?asset[key]=snippets/'.env('APP_NAME').'.liquid',
                'METHOD' => 'DELETE'
            ]);

            // ----- revoke API Access
            $client = new Client(['base_uri' => 'https://'.$shop->name.'/']);
            $response = $client->delete('admin/api_permissions/current.json', [
                'headers' => [
                    'Content-Type'              => 'application/json',
                    'Accept'                    => 'application/json',
                    'Content-Length'            => '0',
                    'X-Shopify-Access-Token'    => $shop->access_token
                ],
                'curl'  => [
                    CURLOPT_RETURNTRANSFER => true
                ]
            ]);

        } catch (\Exception $e) {
            return response([
                'status' => false
            ], 500);
        }

        return response([
            'status' => true
        ], 200);

    }

}