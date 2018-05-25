<?php

namespace App\Http\Middleware;

use Closure;

class Hmac {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        
        
        
        
        dd($request->route()->parameters());
        
        
        
        
        $params = array();
        foreach ($_GET as $param => $value) {
            if ($param != 'signature' && $param != 'hmac') {
                $params[$param] = "{$param}={$value}";
            }
        }
        asort($params);
        $params = implode('&', $params);
        $hmac = isset($_GET['hmac']) ? $_GET['hmac'] : '';
        $calculatedHmac = hash_hmac('sha256', $params, env('SHOPIFY_APP_SECRET'));

//        echo $hmac, '<br>';
//        echo $calculatedHmac;
        if ($hmac == $calculatedHmac) {
            return $next($request);
        }
        return redirect()->to('/');
    }

}
