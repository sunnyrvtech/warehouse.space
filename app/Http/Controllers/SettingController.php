<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ApiSetting;
use App\DeveloperSetting;
use App\Webhook;
use App;

class SettingController extends Controller {

    public function warehouseSetting(Request $request) {
        $data['users'] = auth()->user();
        return view('setting', $data);
    }

    public function apiPostSetting(Request $request) {
        $data = $request->all();
        $this->validate($request, [
            'material_bulk' => 'required',
            'order_status' => 'required',
            'order_detail' => 'required',
            'order_item_complete' => 'required',
            'delete_order_item_complete' => 'required',
            'stock_item' => 'required',
            'stock_item_delete' => 'required',
            'ship_rate' => 'required',
            'warehouse_option' => 'required',
            'track_order' => 'required',
            'stock' => 'required',
        ]);

        $data['user_id'] = auth()->id();
        if ($api_data = ApiSetting::Where('user_id', auth()->id())->first()) {
            $api_data->fill($data)->save();
        } else {
            ApiSetting::create($data);
        }
        return redirect()->back()
                        ->with('success-message', 'Api setting saved successfully!');
    }

    public function devPostSetting(Request $request) {
        $data = $request->all();
        $this->validate($request, [
            'warehouse_number' => 'required|max:50',
            'account_key' => 'required|max:50',
            'percentage_product' => 'required|max:50',
        ]);

        $data['user_id'] = auth()->id();

        // it is used to register webhooks that we needed duton product synchronization
        $user = auth()->user();
        $count_webhook = count(json_decode($user->get_webhook->webhook));
        if ($count_webhook == 1)
            $this->registerWebHooks($user);

        if ($dev_data = DeveloperSetting::Where('user_id', auth()->id())->first()) {
            $dev_data->fill($data)->save();
        } else {
            DeveloperSetting::create($data);
        }
        if (!ApiSetting::Where('user_id', auth()->id())->first())
            ApiSetting::create($data);
        return redirect()->back()
                        ->with('success-message', 'Developer setting saved successfully!');
    }

    public function registerWebHooks($user) {
        $sh = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);

        $webhook_array = array(
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

        $old_value_array = json_decode($user->get_webhook->webhook);
        $update_array[0] = $old_value_array;
        foreach ($webhook_array as $key => $value) {
            $webhook = $sh->call(['URL' => 'webhooks.json', 'METHOD' => 'POST', "DATA" => ["webhook" => array("topic" => $value['name'], "address" => $value['url'], "format" => "json")]]);
            $update_array[$key + 1] = array(
                'name' => $value['name'],
                'webhook_id' => $webhook->webhook->id
            );
        }

        $update_array['webhook'] = json_encode($update_array);
        $webhook = Webhook::Where('user_id', $user->id)->first();
        $webhook->fill($update_array)->save();
        return true;
    }

}
