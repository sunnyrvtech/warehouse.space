<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use SoapClient;
use SoapFault;
use App\User;
use App\Order;
use App;

class OrderController extends Controller {

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

    public function handleOrders(Request $request, $slug) {
        //Log::info('Orders ' . $slug . ':' . json_encode($request->all()));
        $client = $this->_client;
        $shopUrl = $request->headers->get('x-shopify-shop-domain');
        $user = User::Where('shop_url', $shopUrl)->first();
        if ($client != null) {
            if (isset($user->get_dev_setting)) {
                if ($slug == "create") {
                    $result = $this->createOrder($request, $user);
                    Log::info($shopUrl . ' Order ' . $slug . json_encode($result));
                    exit();
                } else if ($slug == "update") {
                    Log::info($shopUrl . ' Order ' . $slug);
                    exit();
                } else if ($slug == "paid" || $slug == "cancelled") {
                    $result = $this->changeOrderStatus($request, $user);
                    Log::info($shopUrl . ' Order ' . $slug . json_encode($result));
                    exit();
                } else {///    this is use to handle delete request
                    Log::info($shopUrl . ' Order ' . $slug);
                    exit();
                }
            }
            Log::info($shopUrl . ' Order ' . $slug . 'not saved account setting yet !');
            exit();
        }
        Log::info($shopUrl . ' Order ' . $slug . 'problem in soap client !');
        exit();
    }

    public function createOrder($request, $user) {
        $client = $this->_client;
        if ($request->get('financial_status') == 'pending') {
            $order_status = 6;
        } else {
            $order_status = 0;
        }


        $billing_first_name = '';
        $billing_last_name = '';
        $shipping_first_name = '';
        $shipping_last_name = '';
        if (isset($request->get('billing_address')['first_name']))
            $billing_first_name = $request->get('billing_address')['first_name'];

        if (isset($request->get('billing_address')['last_name']))
            $billing_last_name = $request->get('billing_address')['last_name'];

        if (isset($request->get('shipping_address')['first_name']))
            $shipping_first_name = $request->get('billing_address')['first_name'];

        if (isset($request->get('shipping_address')['last_name']))
            $shipping_last_name = $request->get('billing_address')['last_name'];

        $order_array = (object) array();

        $article_array = array();
        $order_create_array = array();
        foreach ($request->get('line_items') as $key => $item_data) {
            $article_array[$key] = (object) array(
                        'Article' => $item_data['sku'],
                        'ArticleDescr' => $item_data['name'],
                        'ProductID' => $item_data['variant_id'],
                        'Quantity' => $item_data['quantity']
            );

            $order_create_array[$key] = array(
                'shop_url' => $user->shop_url,
                'account_key' => $user->get_dev_setting->account_key,
                'access_token' => $user->access_token,
                'order_id' => $request->get('id'),
                'item_id' => $item_data['id'],
                'variant_id' => $item_data['variant_id']
            );
        }

        $order_array->ArticlesList = $article_array;
        $order_array->InvNumber = $request->get('id');
        $order_array->Customer = $billing_first_name . ' ' . $billing_last_name;
        $order_array->Comments = '';
        $order_array->ContactPersonName = $shipping_first_name . ' ' . $shipping_last_name;
        $order_array->ContactPersonPhone = $request->get('shipping_address')['phone'];
        $order_array->Shipper = $request->get('processing_method');
        $order_array->InvReference = $request->get('id');
        $order_array->InvStatus = $order_status;
        $order_array->InvDate = date('Y-m-d-H:i', strtotime($request->get('created_at')));
        $order_array->InvDueDate = "";
        $order_array->InvTotal = $request->get('total_price');
        $order_array->InvAmountDue = 0;
        $order_array->ErpTimestamp = date('Y-m-d-H:i');
        $order_array->PartnerKey = '';
        $order_array->DeliverAddress = $request->get('shipping_address')['address1'];
        $order_array->DeliveryPostCodeZIP = $request->get('shipping_address')['zip'];
        $order_array->Country = $request->get('shipping_address')['country'];
        $order_array->CountryCode = $request->get('shipping_address')['country_code'];
        $order_array->City = $request->get('shipping_address')['city'];
        $order_array->StateOrProvinceCode = $request->get('shipping_address')['province_code'];
        $order_array->EmailAddress = $request->get('email');
        $order_array->PaymentMethod = $request->get('gateway');
        $order_array->PaymentDescription = $request->get('gateway');
        $order_array->OrderTotalWeight = $request->get('total_weight');
        $order_array->OrderType = 4;
        $order_array->InvoiceID = "";
        $order_array->ShortCode = "";
        $order_array->Warehouse = $user->get_dev_setting->warehouse_number;
        $order_array->AccountKey = $user->get_dev_setting->account_key;
        //Log::info(' Order update' . json_encode($order_array));
        $result = $client->OrderDetail($order_array);
        if ($result->OrderDetailResult->ErrorMessage == "")
            Order::insert($order_create_array);
        return $result;
    }

    public function changeOrderStatus($request, $user) {
        $client = $this->_client;

        if ($request->get('financial_status') == 'paid') {
            $order_status = 0;
        } else {
            $order_status = 7;
        }

        $order_array = (object) array();
        $order_array->LicenseKey = $user->get_dev_setting->account_key;
        $order_array->InvNumber = $request->get('id');
        $order_array->Status = $order_status;

        $result = $client->ChangeOrderStatus($order_array);
        return $result;
    }

    public function orderDetails(Request $request, $slug) {

        return view('order_detail');
    }

    public function test_order(Request $request) {
        $client = $this->_client;
        $orders = Order::get();
//$shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);
        if ($orders->toArray()) {
            foreach ($orders as $order) {
//                $order_ids = Order::Where('shop_url', '=', $order->shop_url)->pluck('order_id')->toArray();

                $request_array = (object) array();
                $request_array->AccountKey = $order->account_key;
                $request_array->ListInvNumbers = array(0 => $order->order_id);
                //dd($request_array);
                $result = $client->GetOrderShipmentInfo($request_array);
//                echo "<pre>";
//                echo $order->shop_url;
//                print_r($result);
//                die;

                if (isset($result->GetOrderShipmentInfoResult->OrderDetail)) {
                    $result = $result->GetOrderShipmentInfoResult->OrderDetail;

                    // dd($result);
                    if ($result->OrderStatus == 4) {
                        $shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $order->shop_url, 'ACCESS_TOKEN' => $order->access_token]);
                        $item_array[0] = array('id' => $order->item_id);
                        try {
                            $shopify_result = $shopify->call(['URL' => 'orders/' . $result->InvNumber . '/fulfillments.json', 'METHOD' => 'POST', "DATA" => ["fulfillment" => array("location_id" => null, "tracking_number" => null, "line_items" => $item_array)]]);
                        } catch (\Exception $e) {
                            Log::info(' Order id ' . $result->InvNumber . $e->getMessage());
                            continue;
                        }
                        Order::where('id', '=', $order->id)->delete();
                    } elseif ($result->OrderStatus == 0) {
                        $shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $order->shop_url, 'ACCESS_TOKEN' => $order->access_token]);
                        try {
                            $shopify_result = $shopify->call(['URL' => 'orders/' . $result->InvNumber . '/cancel.json', 'METHOD' => 'POST',"DATA"=>['email'=>true]]);
                        } catch (\Exception $e) {
                            Log::info(' Order ' . $result->InvNumber . $e->getMessage());
                            continue;
                        }
                        Order::where('id', '=', $order->id)->delete();
                    }
                }
            }
        }
    }

}
