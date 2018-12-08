<?php

namespace App\Http\Controllers;

use App\Contracts\ShopRepository;
use App\Services\IntegrityService;
use App\Traits\ShopifyTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use RocketCode\Shopify\API;

class IntegrityController extends BaseController
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

    public function __construct(ShopRepository $repository, IntegrityService $integrityService)
    {
        $this->repository = $repository;
        $this->integrity = $integrityService;
    }

    /**
     * Check if the integrity is in place
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public function check(Request $request)
    {
        $shop = $request->session()->get('shop');
        return response()->json($this->integrity->check($shop));
    }

    /**
     * Fix integrity check
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function fix(Request $request)
    {
        $shop = $request->session()->get('shop');
        return response()->json($this->integrity->fix($shop));
    }

    /**
     * Hook method to remove shop from shops table
     * @param Request $request
     * @return void
     */
    public function uninstall(Request $request)
    {
        $shop = $request->input('domain');
        return $this->integrity->uninstall($shop);
    }

    public function cleanUninstall(Request $request)
    {
        return $this->integrity->cleanUninstall($request);
    }

    /**
     * GDPR hook method to remove customer data from App
     * @param Request $request
     * @return void
     */
    public function eraseCustomers(Request $request)
    {
        return $this->integrity->eraseCustomers($request);
    }

    /**
     * GDPR hook method to remove shop data from App
     * @param Request $request
     * @return void
     */
    public function eraseShop(Request $request)
    {
        return $this->integrity->eraseShop($request);
    }

}
