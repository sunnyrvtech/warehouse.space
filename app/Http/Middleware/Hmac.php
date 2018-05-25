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
        
        
        if($request->route()->parameters('slug')){
            
            echo "<pre>";
            print_r($request->route()->parameters());
            
            die;
            
            //$slug = json_decode(base64_decode($request->route()->parameters('slug')));
            
            
           // dd($slug);
            
        }
        
        die('ddd');
        
        return $next($request);
        

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

            $shop_url = $request->route()->parameters('shop_url');
            $user = User::Where('shop_url', $shop_url)->first();
            auth()->login($user);
            return $next($request);
        }
        return redirect()->to('/');
    }

}
