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

    public function index(Request $request, $slug) {
        $data['slug'] = $slug;
        return view('index', $data);
    }

    public function load(Request $request, $slug) {
        $shopify_parameter = json_decode(base64_decode($request->route()->parameters()['slug']));
        $data['slug'] = $slug;
        if (!auth()->check()) {
            die('hello');
            $data['redirect_url'] = route('authenticate', $slug);
        } else {
            $data['redirect_url'] = 'https' . '://' . $shopify_parameter->shop . '/' . 'admin/apps/' . env('SHOPIFY_APP_NAME');
        }

        return view('load', $data);
    }

    public function installShop(Request $request) {
        $shopUrl = $request->get('shop');


        if (!$shopUrl) {
            return 404;
        }


        $user = User::Where('shop_url', $shopUrl);

        if ($user->count() > 0) {
            $slug = base64_encode(json_encode($request->all()));
            return redirect()->route('authenticate', $slug);
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

    public function registerUninstallWebHook($user) {

        $sh = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);

        $webhook = $sh->call(['URL' => 'webhooks.json', 'METHOD' => 'POST', "DATA" => ["webhook" => array("topic" => "app/uninstalled", "address" => route('webhook.uninstalled'), "format" => "json")]]);
        $insert_array = array(
            'name' => "app/uninstalled",
            'webhook_id' => $webhook->webhook->id
        );

        $insert_array['webhook'] = json_encode($insert_array);
        $insert_array['user_id'] = $user->id;
        $insert_array['created_at'] = date('Y-m-d H:i:s');
        $insert_array['updated_at'] = date('Y-m-d H:i:s');

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
                Log::info('Error: invalid request');
                die;
            }
        } catch (\Exception $e) {
            Log::info('Error:' . $e->getMessage());
            die;
        }
        $sh = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $shopUrl, 'ACCESS_TOKEN' => $accessToken]);

        $shopinfo = $sh->call(['URL' => 'shop.json', 'METHOD' => 'GET']);
        $user = User::firstOrNew(['access_token' => $accessToken]);

        $user->name = $shopinfo->shop->shop_owner;
        $user->email = $shopinfo->shop->email;
        $user->shop_name = $shopinfo->shop->name;
        $user->shop_url = $shopUrl;
        $user->status = 1;
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
        $shopUrl = $request->get('domain');
        Log::info('Uninstall:' . $shopUrl);
        $user = User::where(['shop_url' => $shopUrl])->first();
        $user->delete();
    }

    public function getWebhooks(Request $request, $id) {
        $user = User::find($id);

        $sh = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);

        $webhookinfo = $sh->call(['URL' => 'webhooks.json', 'METHOD' => 'GET']);
        dd($webhookinfo);
    }

    public function storeAuthenticate(Request $request, $slug) {
        $shopify_parameter = json_decode(base64_decode($slug));
        $user = User::Where('shop_url', $shopify_parameter->shop)->first();

        if (!$user->get_webhook)
            $this->registerUninstallWebHook($user);

        if (isset($shopify_parameter->model) && $shopify_parameter->model == 'order_details') {
            return redirect()->route('warehouse.order.details', $slug);
        }
        return redirect()->route('dashboard', $slug);
    }

}
