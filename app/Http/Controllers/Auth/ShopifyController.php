<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Webhook;
use App;
use Log;
use Carbon\Carbon;

class ShopifyController extends Controller {

    public function index(Request $request) {
        return view('index');
    }

    public function installShop(Request $request) {
        $shopUrl = $request->get('shop');

        if (!$shopUrl) {
            return 404;
        }
        $user = User::Where('shop_url', $shopUrl);
        if ($user->count() > 0) {
            if (!auth()->check())
                auth()->login($user);
            if (!$user->first()->get_webhook)
                $this->registerWebHooks($user->first());
            return view('index');
        }
        return $this->doAuth($shopUrl);
    }

    public function doAuth($shopUrl) {
        $scope = explode(',', env('SHOPIFY_APP_PERMISSIONS'));
        $shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $shopUrl, 'ACCESS_TOKEN' => '']);
        $permission_url = $shopify->installURL([
            'permissions' => $scope,
            'redirect' => env('SHOPIFY_APP_REDIRECT_URL')
        ]);
        return redirect()->to($permission_url);
    }

    public function registerWebHooks($user) {

        $sh = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);

        $webhook_array = array(
            [
                'name' => "app/uninstalled",
                'url' => route('webhook.uninstalled')
            ],
            [
                'name' => "inventory_items/create",
                'url' => route('webhook.inventory_items', 'create')
            ],
            [
                'name' => "inventory_items/update",
                'url' => route('webhook.inventory_items', 'update')
            ],
            [
                'name' => "inventory_items/delete",
                'url' => route('webhook.inventory_items', 'delete')
            ],
            [
                'name' => "products/create",
                'url' => route('webhook.products', 'create')
            ],
            [
                'name' => "products/update",
                'url' => route('webhook.products', 'update')
            ],
            [
                'name' => "products/delete",
                'url' => route('webhook.products', 'delete')
            ],
            [
                'name' => "orders/create",
                'url' => route('webhook.orders', 'delete')
            ],
            [
                'name' => "orders/updated",
                'url' => route('webhook.orders', 'update')
            ],
            [
                'name' => "orders/delete",
                'url' => route('webhook.orders', 'delete')
            ],
            [
                'name' => "orders/cancelled",
                'url' => route('webhook.orders', 'delete')
            ],
        );

        $insert_array = array();
        foreach ($webhook_array as $key => $value) {
            $webhook = $sh->call(['URL' => 'webhooks.json', 'METHOD' => 'POST', "DATA" => ["webhook" => array("topic" => $value['name'], "address" => $value['url'], "format" => "json")]]);
            $insert_array[$key] = array(
                'name' => $value['name'],
                'webhook_id' => $webhook->webhook->id
            );
        }

        $insert_array['webhook'] = json_encode($insert_array);
        $insert_array['user_id'] = $user->id;
        $insert_array[$key]['created_at'] = date('Y-m-d H:i:s');
        $insert_array[$key]['updated_at'] = date('Y-m-d H:i:s');

//        dd($insert_array);

        Webhook::create($insert_array);
        return true;
    }

    public function processOAuthResultRedirect(Request $request) {
        $code = $request->get('code');
        $shopUrl = $request->get('shop');

        $redirect_url = 'https' . '://' . $shopUrl . '/' . 'admin/apps/' . env('SHOPIFY_APP_NAME');

        $sh = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $shopUrl]);

        try {
            $verify = $sh->verifyRequest($request->all());
            if ($verify) {
                $accessToken = $sh->getAccessToken($code);
            } else {
                echo "invalid request";
                die;
            }
        } catch (Exception $e) {
            echo '<pre>Error: ' . $e->getMessage() . '</pre>';
            die;
        }
// $chars is used to generate random password 
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";

        $sh = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $shopUrl, 'ACCESS_TOKEN' => $accessToken]);

        $shopinfo = $sh->call(['URL' => 'shop.json', 'METHOD' => 'GET']);
        $user = User::firstOrNew(['access_token' => $accessToken]);

        $user->name = $shopinfo->shop->shop_owner;
        $user->email = $shopinfo->shop->email;
        $user->shop_name = $shopinfo->shop->name;
        $user->shop_url = $shopUrl;
        $user->password = substr(str_shuffle($chars), 0, 10);
        $user->access_token = $accessToken;
        $user->created_at = Carbon::now();
        $user->updated_at = Carbon::now();


        if (!$user->exists) {
            $user->save();
            return redirect()->to($redirect_url);
        } else {
            return redirect()->to($redirect_url);
        }
    }

    public function handleAppUninstallation(Request $request) {
        Log::info('Uninstall:' . json_encode($request->all()));
        $shopUrl = $request->get('domain');
        $user = User::where(['shop_url' => $shopUrl])->first();
        $user->delete();
    }

    public function getWebhooks(Request $request, $id) {
        $user = User::find($id);

        $sh = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);

        $webhookinfo = $sh->call(['URL' => 'webhooks.json', 'METHOD' => 'GET']);
        dd($webhookinfo);
    }

    public function warehouseSetting(Request $request) {
        return view('setting');
    }

}
