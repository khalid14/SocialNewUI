<?php
/**
 * Created by PhpStorm.
 * User: Bilal
 * Date: 7/13/2018
 * Time: 3:38 PM
 */

namespace App\Traits;


use App\Models\Shop;
use Illuminate\Http\Request;
use RocketCode\Shopify\API;

trait ShopifyTrait
{

    /**
     * @param mixed $shop
     * @param null $token
     * @return API
     */
    public function getShopifyObj($shop, $token = null)
    {
        $name = $shop instanceof Shop ? $shop->name : $shop;
        $token = $shop instanceof Shop ? $shop->access_token : $token;

        return \App::make('ShopifyAPI', [
            'API_KEY'       => env('SHOPIFY_API_KEY'),
            'API_SECRET'    => env('SHOPIFY_API_SECRET'),
            'SHOP_DOMAIN'   => $name,
            'ACCESS_TOKEN'  => $token
        ]);
    }

    public function verifyWebHook(Request $request)
    {
        $data = $request->getContent();
        $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');

        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_API_SECRET'), true));
        return ($hmacHeader == $calculatedHmac);
    }

    public function verifyRequest(Request $request)
    {
        $query_params = $request->query->all();
        if (!isset($query_params['timestamp'])) return false;
        $seconds_in_a_day = 24 * 60 * 60;
        $older_than_a_day = $query_params['timestamp'] < (time() - $seconds_in_a_day);
        if ($older_than_a_day) return false;

        $hmac = $query_params['hmac'];
        unset($query_params['signature'], $query_params['hmac']);

        foreach ($query_params as $key=>$val) $params[] = "$key=$val";
        sort($params);

        return (hash_hmac('sha256', implode('&', $params), env('SHOPIFY_API_SECRET')) === $hmac);
    }

}