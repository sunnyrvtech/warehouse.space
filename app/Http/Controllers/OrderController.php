<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use SoapClient;
use SoapFault;
use App\User;
use App\DeveloperSetting;
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
        $client = $this->_client;

        $shopify_parameter = json_decode(base64_decode($slug));
        $order_id = $shopify_parameter->id;
        $user = auth()->user();
        $request_array = (object) array();
        $request_array->AccountKey = $user->get_dev_setting->account_key;
        $request_array->ListInvNumbers = array($order_id);

        $warehouse_order = $client->GetOrderShipmentInfo($request_array);
        echo htmlentities($client->__getLastRequest());
        echo "<pre>";
        print_r($request_array);
        dd($warehouse_order);
        if (isset($warehouse_order->GetOrderShipmentInfoResult->OrderShipmentInfo)) {
            $shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);

            $warehouse_order = $warehouse_order->GetOrderShipmentInfoResult->OrderShipmentInfo;
            if (count($warehouse_order) == 1) {
                $single_array[0] = $warehouse_order;
                $warehouse_order = $single_array;
            }

            $orders = $shopify->call(['URL' => 'orders/' . $warehouse_order[0]->InvNumber . '.json?fields=id,financial_status,created_at,line_items', 'METHOD' => 'GET']);
            // dd($orders);

            $order_details = (object) array();
            $order_details->order_id = $orders->order->id;
            $order_details->payment_status = $orders->order->financial_status;
            $order_details->order_date = date('M d,Y', strtotime($orders->order->created_at));
            foreach ($orders->order->line_items as $key => $order) {
                $item = (object) array();
                $item->variant_id = $order->variant_id;
                $item->product_name = $order->title;
                $item->variant_title = $order->variant_title;
                $item->product_link = 'https://' . $user->shop_url . '/admin/products/' . $order->product_id . '/variants/' . $order->variant_id;
//                $item->description = $warehouse_order[$key]->Description;
                $item->sku = $order->sku;
//                $item->quantity = $order->quantity;
//                $item->price = $order->price;
                $item->dispatched = $warehouse_order[$key]->Dispatched != null ? date('M d,Y', strtotime($warehouse_order[$key]->Dispatched)) : '';
                $item->packed = $warehouse_order[$key]->Packed != null ? date('M d,Y', strtotime($warehouse_order[$key]->Packed)) : '';
                $item->picked = $warehouse_order[$key]->Picked != null ? date('M d,Y', strtotime($warehouse_order[$key]->Picked)) : '';
                $item->warehouse = $warehouse_order[$key]->Warehouse;
//                $item->you_tube_url = $warehouse_order[$key]->YouttubeUrl;
                if ($warehouse_order[$key]->OrderStatus == 0)
                    $item_status = "Pending";
                elseif ($warehouse_order[$key]->OrderStatus == 2)
                    $item_status = "Selected/picked";
                elseif ($warehouse_order[$key]->OrderStatus == 3)
                    $item_status = "In Progress";
                elseif ($warehouse_order[$key]->OrderStatus == 4)
                    $item_status = "completed";
                elseif ($warehouse_order[$key]->OrderStatus == 6)
                    $item_status = "On Hold";
                elseif ($warehouse_order[$key]->OrderStatus == 7)
                    $item_status = "Cancelled";
                else
                    $item_status = "";

                $item->item_status = $item_status;
                $order_details->items[$key] = $item;
            }
            // dd($order_details);
            $data['order_details'] = $order_details;
            return view('order_detail', $data);
        }

        return redirect()->route('dashboard')->with('error-message', 'sorry! this order is not found in warehouse.');
    }

    public function updateOrderStatus($id, $no, $token) {
        $client = $this->_client;
        $user = DeveloperSetting::Where([['warehouse_number', $no]])->first();


//$shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);
        if (isset($user->get_user) && $token == env('WAREHOUSE_TOKEN')) {

            $request_array = (object) array();
            $request_array->AccountKey = $user->account_key;
            $request_array->ListInvNumbers = array($id);
            $warehouse_order = $client->GetOrderShipmentInfo($request_array);
            echo "<pre>";
            print_r($request_array);
            print_r($warehouse_order);
            die;

            if (isset($warehouse_order->GetOrderShipmentInfoResult->OrderShipmentInfo)) {
                $shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->get_user->shop_url, 'ACCESS_TOKEN' => $user->get_user->access_token]);

                $warehouse_order = $warehouse_order->GetOrderShipmentInfoResult->OrderShipmentInfo;
                try {
                    $orders = $shopify->call(['URL' => 'orders/' . $id . '.json?fields=id,financial_status,fulfillment_status,created_at,line_items', 'METHOD' => 'GET']);
                } catch (\Exception $e) {
                    return json_encode(array('success' => false));
                }
                //dd($orders);

                if ($warehouse_order->OrderStatus == 4 && $orders->order->fulfillment_status == null && isset($warehouse_order->Shipments->ShipmentDetail)) {
                    $warehouse_shipment = $warehouse_order->Shipments->ShipmentDetail;
                    if (count($warehouse_order->Shipments->ShipmentDetail) == 1) {
                        $shipment_array[0] = $warehouse_order->Shipments->ShipmentDetail;
                        $warehouse_shipment = $shipment_array;
                    }
                    echo "<pre>";
                    print_r($warehouse_shipment);

                    foreach ($warehouse_shipment as $shipment) {
                        $articles = $shipment->Articles->Article;
                        if (count($shipment->Articles->Article) == 1) {
                            $article_array[0] = $shipment->Articles->Article;
                            $articles = $article_array;
                        }
                        $product_id_array = array_column($articles, 'ProductID');
                        //print_r($product_id_array);
                        $item_ids_array = array();
                        foreach ($orders->order->line_items as $key => $order) {
                            //echo $order->variant_id.'<br>';
                            if (in_array($order->variant_id, $product_id_array)) {
                                $item_ids_array[$key] = $order->id;
                            }
                        }
                       // echo count($warehouse_shipment);
                        //dd($item_ids_array);
                        
                        
                        try {
                        $shopify_result = $shopify->call(['URL' => 'orders/' . $id . '/fulfillments.json', 'METHOD' => 'POST', "DATA" => ["fulfillment" => array("location_id" => null, "tracking_number" => $track_info_array, "line_items" => $item_ids_array)]]);
                        } catch (\Exception $e) {
                            Log::info('Order status update error ' . $id . $e->getMessage());
                            return json_encode(array('success' => false));
                        }
                    }





//                    $item_ids_array = array();
//                    $track_info_array = array();
//                    foreach ($orders->order->line_items as $key => $order) {
//                        $item_ids_array[$key] = $order->id;
//                        $track_info_array[$key] = $warehouse_order[$key]->Shipments->ShipmentDetail->TrackingNuber;
//                    }
//                    try {
//                        $shopify_result = $shopify->call(['URL' => 'orders/' . $id . '/fulfillments.json', 'METHOD' => 'POST', "DATA" => ["fulfillment" => array("location_id" => null, "tracking_number" => $track_info_array, "line_items" => $item_ids_array)]]);
//                    } catch (\Exception $e) {
//                        Log::info('Order status update error ' . $id . $e->getMessage());
//                        return json_encode(array('success' => false));
//                    }
                } elseif ($warehouse_order[0]->OrderStatus == 7) {
                    try {
                        $shopify_result = $shopify->call(['URL' => 'orders/' . $id . '/cancel.json', 'METHOD' => 'POST', "DATA" => ['email' => true]]);
                    } catch (\Exception $e) {
                        Log::info('Order status update error' . $id . $e->getMessage());
                        return json_encode(array('success' => false));
                    }
                }
                return json_encode(array('success' => true));
            }
        }
        return json_encode(array('success' => false));
    }

}
