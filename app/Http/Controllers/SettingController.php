<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ApiSetting;
use App\DeveloperSetting;
use App\Webhook;
use App;
use Log;
use SoapClient;
use SoapFault;

class SettingController extends Controller {

    protected $_client = null;

    /**
     * WarehouseSpace_Warehouse_Model_Api constructor.
     */
    public function __construct() {
        $debug = true;
        $wsdl = env('WSDL_URL');
        try {
            $this->_client = new SoapClient($wsdl, array(
                'connection_timeout' => 5000,
                'cache_wsdl' => $debug ? WSDL_CACHE_NONE : WSDL_CACHE_MEMORY,
                'trace' => true,
                'exceptions' => true,
                'soap_version' => SOAP_1_1
                    )
            );
        } catch (SoapFault $fault) {
            Log::info('Soap client error: ' . $fault->getMessage());
        }
    }

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
        $user = auth()->user();
        if (!isset($user->get_dev_setting)) {
            $this->validate($request, [
                'warehouse_number' => 'required|max:50|unique:developer_settings',
                'account_key' => 'required|max:50|unique:developer_settings',
            ]);
        } else {
            $id = $user->get_dev_setting->id;
            $this->validate($request, [
                'warehouse_number' => 'required|max:50|unique:developer_settings,warehouse_number,' . $id,
                'account_key' => 'required|max:50|unique:developer_settings,account_key,' . $id,
//            'percentage_product' => 'required|max:50',
            ]);
        }

        $data['user_id'] = auth()->id();

        // it is used to register webhooks that we needed duton product synchronization
        $count_webhook = count(json_decode($user->get_webhook->webhook));
        if ($count_webhook == 1)
            $this->registerWebHooks($user);

        if (isset($user->get_dev_setting)) {
            $dev_data = $user->get_dev_setting;
            //if ($user->get_dev_setting->warehouse_token == null)
                $token = $this->getWarehouseToken($user);
                dd($token);
            $dev_data->fill($data)->save();
        } else {
            $token = $this->getWarehouseToken($user);
            dd($token);
            DeveloperSetting::create($data);
        }

        if (!isset($user->get_api_setting))
            ApiSetting::create($data);
        // return redirect()->route('warehouse.product.sync');
        return redirect()->back()
                        ->with('success-message', 'Developer setting saved successfully!');
    }

    public function getWarehouseToken($user) {
        $client = $this->_client;
        $request_array = (object) array();
        $request_array->AccountKey = $user->get_dev_setting->account_key;
        $request_array->Warehouse = $user->get_dev_setting->warehouse_number;
        $request_array->ShopName = $user->shop_name;
        $request_array->ShopURL = $user->shop_url;
        $request_array->OrderServiceURL = url('/api/webhooks/order');
        $request_array->StockAdjustmentURL = '';
        $request_array->ShopIP = $_SERVER['REMOTE_ADDR'];
        $request_array->ShopLanguage = '';
        $request_array->Enable = true;
        $request_array->AdminEmail = $user->email;
        
        dd($request_array);
        $result = $client->RegisterStore($request_array);
        return $result;
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
                'url' => route('webhook.orders', 'create')
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
                'name' => "orders/paid",
                'url' => route('webhook.orders', 'paid')
            ],
            [
                'name' => "orders/cancelled",
                'url' => route('webhook.orders', 'cancelled')
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
