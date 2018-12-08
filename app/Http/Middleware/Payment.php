<?php

namespace App\Http\Middleware;

use App\Contracts\ShopRepository;
use App\Models\Shop;
use App\Services\BillingService;
use App\Traits\ShopifyTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class Payment
{
    use ShopifyTrait;

    /**
     * @var ShopRepository
     */
    protected $shopRepository;

    /**
     * @var BillingService
     */
    protected $billingService;


    public function __construct(ShopRepository $shopRepository, BillingService $billingService)
    {
        $this->shopRepository = $shopRepository;
        $this->billingService = $billingService;
    }

    /**
     * Only proceed if billing is enable, and we find a record in billings table
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $shop = $request->has('shop') ? $request->get('shop') : ($request->shop != null ? $request->shop : null);
        /** @var $shopObj Shop */
        $shopObj = $this->shopRepository->findByField('name', $shop)->first();
        if ($shopObj != null) {
            $freePass = $shopObj->free_pass;
            $freePassCheck = $shopObj->last_checked;
            $affiliate = $shopObj->plan_name == 'affiliate';

            if (!$affiliate) {
                $this->syncFreePass($shopObj);
                if ($freePassCheck != null && $freePass == false) {
                    return $this->freePassExpired($request);
                }

                if (env('SHOPIFY_CHARGE') && $shopObj->billing != null) {

                    $this->billingService->setShop($shopObj);
                    if ($this->billingService->getStatus() != 'active') {
                        return $this->requestBilling($request);
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return string
     */
    private function requestBilling(Request $request)
    {
        $uri = $request->getRequestUri();
        if (starts_with($uri, '/pull')) {
            return response('// App billing not configured.');
        } else {
            return redirect()->route('pages.requestBilling');
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    private function freePassExpired(Request $request)
    {
        $uri = $request->getRequestUri();
        if (starts_with($uri, '/pull')) {
            return response('// Your free pass expired. Please reinstall the app.');
        } else {
            return redirect()->route('pages.freePassExpired');
        }
    }

    private function syncFreePass(Shop $shop)
    {
        if ($shop->free_pass && $shop->last_checked != null) {
            $now = Carbon::now();
            $last = $shop->last_checked;

            $nowDate = $now->format('Y-m-d');
            $lastCheckDate = $last->format('Y-m-d');

            // ----- only check if a day has passed since last checked
            if ($nowDate != $lastCheckDate) {

                // ----- check if should get free pass
                $pass = $shop->shouldGetFreePass();
                $shop->free_pass = $pass;
                $shop->last_checked = $now;
                $shop->save();
            }
        }
    }

}
