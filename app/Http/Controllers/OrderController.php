<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use SoapClient;
use SoapFault;
use App\User;
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
        if ($client != null && $slug == "create") {
            $shopUrl = $request->headers->get('x-shopify-shop-domain');
            $user = User::Where('shop_url', $shopUrl)->first();
            if (isset($user->get_dev_setting)) {

//                if ($request->get('fulfillment_status') == 'fulfilled') {
//                    Log::info('Orders ' . $slug . "Order already fulfilled");
//                    exit();
//                }
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
                foreach ($request->get('line_items') as $key => $item_data) {
                    $article_array[$key] = (object) array(
                                'Article' => $item_data['sku'],
                                'ArticleDescr' => $item_data['name'],
                                'ProductId' => $item_data['variant_id'],
                                'Quantity' => $item_data['quantity']
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

                $result = $client->OrderDetail($order_array);
                Log::info('Orders ' . $slug . json_encode($result));
                exit();
            }
            Log::info('Orders ' . $slug . 'not saved account setting yet !');
            exit();
        } else if ($client != null && $slug == "update") {
            $shopUrl = $request->headers->get('x-shopify-shop-domain');
            $user = User::Where('shop_url', $shopUrl)->first();
            if (isset($user->get_dev_setting)) {
                if ($request->get('financial_status') == 'pending') {
                    $order_status = 6;
                } else {
                    $order_status = 0;
                }
                $order_array = (object) array();
                $order_array->WarehouseID = $user->get_dev_setting->warehouse_number;
                $order_array->LicenseKey = $user->get_dev_setting->account_key;
                $order_array->InvNumber = $request->get('id');
                $order_array->Status = $order_status;

                $result = $client->ChangeOrderStatus($order_array);
                Log::info('Orders ' . $slug . json_encode($result));
                exit();
            }
            Log::info('Orders ' . $slug . 'not saved account setting yet !');
            exit();
        } else {
            if ($slug == "create")
                Log::info('Orders ' . $slug . 'problem in soap client !');
            else
                Log::info('Orders ' . $slug);
            exit();
        }
    }

    public function test_order(Request $request) {
        //Log::info('Orders ' . $slug . ':' . json_encode($request->all()));
        $slug = 'update';
        $client = $this->_client;
//        echo "<pre>";
//        print_r($client);
//        
//        die;

        if ($client != null && ($slug == "create" || $slug == "update")) {
            $shopUrl = 'wsdev01.myshopify.com';
            $user = User::Where('shop_url', $shopUrl)->first();

            $shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);

            $orderinfo = $shopify->call(['URL' => 'orders/384264798261.json', 'METHOD' => 'GET']);
            $orderinfo = $orderinfo->order;

       //     dd($orderinfo);

 $order_array = (object) array();
                $order_array->WarehouseID = $user->get_dev_setting->warehouse_number;
                $order_array->LicenseKey = $user->get_dev_setting->account_key;
                $order_array->InvNumber = $orderinfo->id;
                $order_array->Status = 7;
                echo "<pre>";
                print_r($order_array);
            die;
        }
    }

}
