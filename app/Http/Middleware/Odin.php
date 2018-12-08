<?php

namespace App\Http\Middleware;

use App\Traits\ShopifyTrait;
use Closure;

class Odin
{

    use ShopifyTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->verifyWebHook($request) ||
            $this->verifyRequest($request) ||
            $request->session()->has('shop'))
        {
            return $next($request);
        } else {
            return response(view('welcome'));
        }
    }

}
