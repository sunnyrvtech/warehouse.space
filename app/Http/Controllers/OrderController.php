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
        if ($client != null && ($slug == "create" || $slug == "update")) {
            $shopUrl = $request->headers->get('x-shopify-shop-domain');
            $user = User::Where('shop_url', $shopUrl)->first();
            if (isset($user->get_dev_setting)) {
                $billing_first_name = '';
                $billing_last_name = '';
                $shipping_first_name = '';
                $shipping_last_name = '';
                if (isset($request->get('billing_address')->first_name))
                    $billing_first_name = $request->get('billing_address')->first_name;

                if (isset($request->get('billing_address')->last_name))
                    $billing_last_name = $request->get('billing_address')->last_name;

                if (isset($request->get('shipping_address')->first_name))
                    $shipping_first_name = $request->get('billing_address')->first_name;

                if (isset($request->get('shipping_address')->last_name))
                    $shipping_last_name = $request->get('billing_address')->last_name;

                $order_array = array();
                $order_array['InvNumber'] = $request->get('id');
                $order_array['Customer'] = $billing_first_name . ' ' . $billing_last_name;
                $order_array['Comments'] = '';
                $order_array['ContactPersonName'] = $shipping_first_name . ' ' . $shipping_last_name;
                //$order_array['ContactPersonPhone'] = $request->get('shipping_address')->phone;
                $order_array['Shipper'] = $request->get('processing_method');
                $order_array['InvReference'] = $request->get('id');
                $order_array['InvStatus'] = 0;
                $order_array['InvDate'] = date('Y-m-d-H:i', strtotime($request->get('created_at')));
//                $order_array['InvDueDate'] = $request->get('id');
                $order_array['InvTotal'] = $request->get('total_price');
                $order_array['InvAmountDue'] = 0;
//                $order_array['ErpTimestamp'] = $request->get('id');
                $order_array['PartnerKey'] = '';
              //  $order_array['DeliverAddress'] = $request->get('shipping_address')->address1;
               // $order_array['DeliveryPostCodeZIP'] = $request->get('shipping_address')->zip;
                //$order_array['Country'] = $request->get('shipping_address')->country;
                //$order_array['CountryCode'] = $request->get('shipping_address')->country_code;
                //$order_array['City'] = $request->get('shipping_address')->city;
               // $order_array['StateOrProvinceCode'] = $request->get('shipping_address')->province_code;
                $order_array['EmailAddress'] = $request->get('email');
                $order_array['PaymentMethod'] = $request->get('gateway');
                $order_array['PaymentDescription'] = $request->get('gateway');
                $order_array['OrderTotalWeight'] = $request->get('total_weight');
//                $article_array = array();
//                foreach ($request->get('line_items') as $key => $item_data) {
//                    $article_array[$key] = ['quantity' => $item_data->quantity, 'name' => $item_data->title];
//                }


               // $order_array['ArticleList'] = $article_array;
                //$result = $client->MaterialBulk($final_product_array);
                Log::info('Orders ' . $slug . json_encode($order_array));
                exit();
            }
            Log::info('Orders ' . $slug . 'not saved account setting yet !');
            exit();
        } else {
            if ($slug != "delete")
                Log::info('Orders ' . $slug . 'problem in soap client !');
            exit();
        }
    }

}
