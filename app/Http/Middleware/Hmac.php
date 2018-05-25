<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class Hmac {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {


        if (isset($request->route()->parameters()['slug'])) {

            $shopify_parameter = json_decode(base64_decode($request->route()->parameters()['slug']));

            $params = array();
            foreach ($shopify_parameter as $param => $value) {
                if ($param != 'signature' && $param != 'hmac') {
                    $params[$param] = "{$param}={$value}";
                }
            }
            asort($params);
            $params = implode('&', $params);
            $hmac = $shopify_parameter->hmac;
            $calculatedHmac = hash_hmac('sha256', $params, env('SHOPIFY_APP_SECRET'));

            if ($hmac == $calculatedHmac) {
                $shop_url = $request->route()->parameters('shop_url');
                
                echo $shop_url;
                die;
                
                $user = User::Where('shop_url', $shop_url)->first();
                auth()->login($user);
                return $next($request);
            }
            return redirect()->to('/');
        }
        return $next($request);
    }

}
