<?php

namespace App\Http\Controllers;

use App\Contracts\ShopRepository;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class PullController extends Controller
{

    /**
     * @var ShopRepository
     */
    protected $repository;

    public function __construct(ShopRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * load minimal footprint to see if the theme injection is loaded
     * if loaded, abort, else load hooks manually and also trigger integrity check
     * @param $shop
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function lazy($shop)
    {
        $shop = $this->repository->findByField('name', $shop)->first();
        $hook = '';

        if ($shop != null) {
            $props = [
                'app' => env('APP_NAME'),
                'base' => env('APP_URL'),
                'shop' => $shop->name
            ];

            $hook = 'var lazyTargetGDPR = '.json_encode($props).';'.PHP_EOL
                .Storage::disk('local')->get('hook/lazy.js');
        }

        return response(
            $hook,
            200
        )->header('Content-Type', 'text/javascript');
    }

    /**
     * @param $shop
     * @param bool $integrity
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function js($shop, $integrity = false)
    {
        $integrity = boolval($integrity);
        $shop = $this->repository->findByField('name', $shop)->first();
        if ($shop == null) {
            return response(
                'var '.env('APP_NAME').' = "uninstalled";',
                200
            )->header('Content-Type', 'text/javascript');
        }

        $hook = Storage::disk('local')->get('hook/hook.min.js');

        return response(
            'var '.env('APP_NAME').' = '.$this->getParams($shop).';'.PHP_EOL
            .$hook,
            200
        )->header('Content-Type', 'text/javascript');
    }

    /**
     * @param $shop
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function css($shop)
    {
        $shop = $this->repository->findByField('name', $shop)->first();
        if ($shop == null) {
            return response(
                '.'.env('APP_NAME').' { content : "uninstalled"; }',
                200
            )->header('Content-Type', 'text/css');
        }

        $hook = Storage::disk('local')->get('hook/hook.css');
        return response($hook, 200)
            ->header('Content-Type', 'text/css');
    }

    /**
     * load any data you want to be passed to hook
     * @param Shop $shop
     * @return string
     */
    private function getParams(Shop $shop)
    {
        $data = array_merge($shop->setting->transform(), [
            'shop'  => $shop->name,
            'baseURL'  => env('APP_URL')
        ]);
        return json_encode($data);
    }
}
