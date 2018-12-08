<?php

namespace App\Http\Controllers;

use App\Contracts\ShopRepository;
use App\Models\Shop;
use App\Services\BillingService;
use App\Services\IntegrityService;
use App\Traits\ShopifyTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class ShopifyController extends BaseController
{

    use ShopifyTrait;
    /**
     * @var ShopRepositoryEloquent
     */
    protected $repository;

    /**
     * @var IntegrityService
     */
    protected $integrity;

    /**
     * @var BillingService
     */
    protected $billing;

    public function __construct(ShopRepository $repository, IntegrityService $integrityService, BillingService $billingService)
    {
        $this->repository = $repository;
        $this->integrity = $integrityService;
        $this->billing = $billingService;
    }

    /**
     * Action method which fires up whenever the root url (/) is accessed
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function access(Request $request)
    {
        $shop = $request->has('shop') ? $request->get('shop') :
            ($request->session()->has('shop') ? $request->session()->get('shop') : null);
        $shopObj = $this->repository->findByField('name', $shop)->first();

        // ----- check if the shop is already set, then use it
        if ($shopObj != null) {
            return $this->initSPA($shopObj);
        } elseif ($shop != null) {
            return $this->doAuth($shop);
        }
    }

    /**
     * action for shopify callback URL, to store access token and login the user.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function authCallback()
    {
        if (isset($_GET['code'])) {
            $shop = $_GET['shop'];
            $code = $_GET['code'];
            $shopInfo = [];

            $shopify = $this->getShopifyObj($shop, '');
            $accessToken = $shopify->getAccessToken($code);

            session(['shop' => $shop]);
            $shopify->setup(['ACCESS_TOKEN' => $accessToken]);

            try {
                $shopInfo = $shopify->call(['URL' => 'shop.json', 'METHOD' => 'GET']);
            } catch (\Exception $e) {
                echo $e->getMessage();
                exit;
            }

            $shop = $this->integrity->register($shop, $accessToken, $shopInfo->shop);
            $redirect = $this->billing->setShop($shop)->inspect();

            return redirect($redirect);
        }
    }

    /**
     * helper method to perform shopify auth
     * @param $shop
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doAuth($shop)
    {
        $shopify = $this->getShopifyObj($shop, '');
        $premissionURL = $shopify->installURL([
            'permissions'   => config('shopify.permissions'),
            'redirect'      => route('shopify-auth-callback')
        ]);

        return redirect($premissionURL);
        //return view('shopify.escapeIFrame', ['installUrl' => $premissionURL]);
    }

    public function chargeCallback(Request $request)
    {
        if ($request->has('charge_id')) {
            return $this->billing->actUponDecision($request);
        } else {
            return '';
        }
    }

    public function dev()
    {
        return view('shopify.index',
            [
                'shop' => 'asdasd',
                'owner' => 'text',
                'email' => 'text',
                'api_key' => env('SHOPIFY_API_KEY')
            ]);
    }

    /**
     * AUTH done, init SPA ...
     * @param $shop
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function initSPA(Shop $shop)
    {
        $UnitOfWork = [
            'shop' => $shop->name,
            'owner' => $shop->owner,
            'email' => $shop->email,
            'api_key' => env('SHOPIFY_API_KEY')
        ];

        session($UnitOfWork);
        return view('shopify.index', $UnitOfWork);
    }

    public function freePass()
    {
        return view('free-pass');
    }

    public function affiliate()
    {
        return view('affiliate');
    }

    public function freePassExpired(Request $request)
    {
        return view('free-pass-expired');
    }

    /**
     * Show user the billing status and present with a appropriate action
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function requestBilling(Request $request)
    {
        $requestBillingData = $this->integrity->acceptBillingStatus($request);
        return view('accept-billing', $requestBillingData);
    }

    /**
     * flow for action when user declines payment, and clicks on "setup payment" on next page
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function restartBilling(Request $request)
    {
        $shop = $request->has('shop') ? $request->get('shop') :
            ($request->session()->has('shop') ? $request->session()->get('shop') : null);
        $shop = $this->repository->findByField('name', $shop)->first();
        $shop->billing->forceDelete();

        $this->billing->setShop($shop);
        return redirect($this->billing->enroll());
    }

    public function kiki(Request $request)
    {
        $shop = $this->repository->findByField('name', 'bilal-dev-store.myshopify.com')->first();
        $billing = $this->billing->setShop($shop);
        var_dump($billing->calculateTrial());
        exit;
    }

    public function contact(Request $request)
    {
        $shop = $request->has('shop') ? $request->get('shop') :
            ($request->session()->has('shop') ? $request->session()->get('shop') : null);
        $shop = $this->repository->findByField('name', $shop)->first();
        return view('contact', ['shop' => $shop]);
    }

    public function contactSend(Request $request)
    {
        return response(json_encode(['status' => 'success']), '200', [
            'Content-Type' => 'application/json'
        ]);
    }

}
