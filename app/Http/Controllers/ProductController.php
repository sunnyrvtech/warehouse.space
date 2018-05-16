<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use SoapClient;
use SoapFault;
use App;

class ProductController extends Controller {

    protected $_accountKey = '';
    protected $_warehouseNumber = '';
    protected $_client = null;
    protected $_shopify = null;
    protected $_user;

    /**
     * WarehouseSpace_Warehouse_Model_Api constructor.
     */
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->_user = auth()->user();
            $user = $this->_user;
            if (isset($user->get_dev_setting)) {
                $this->_shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);
                $this->_accountKey = $user->get_dev_setting->account_key;
                $this->_warehouseNumber = $user->get_dev_setting->warehouse_number;
                $debug = true;
                $wsdl = $user->get_dev_setting->wsdl_url;
                try {
                    $this->_client = new SoapClient($wsdl, array(
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
            return $next($request);
        });
    }

    public function handleProducts(Request $request, $slug) {
        Log::info('Products ' . $slug . ':' . json_encode($request->all()));
    }

    public function synchronizeProducts(Request $request) {
        $user = $this->_user;
        $client = $this->_client;
        $shopify = $this->_shopify;

        if ($client != null && $shopify != null) {
            $limit = $user->get_dev_setting->page_size;
            $page = $user->get_dev_setting->offset;
            $productinfo = $shopify->call(['URL' => 'products.json?limit=1&page=2', 'METHOD' => 'GET']);


            $product_array = array();
            foreach ($productinfo as $key => $product) {
                dd($product);
                
//                foreach($product['variants'] as $item_value){
//                    echo $item_value->id;
//                }
                
                
//                $item_array = (object) array();
//                $item_array->Article = $product->id;
//                $item_array->Description = 'ff' . $i;
//                $item_array->UOM = 'each';
//                $item_array->BuyPrice = 0;
//                $item_array->SellPrice = 12;
//                $item_array->Supplier = "";
//                $item_array->Images = "";
//                $item_array->Manufacturer = "";
//                $item_array->MinQuantity = 10;
//                $item_array->ItemWeight = 10;
//                $item_array->ItemHeight = 10;
//                $item_array->ItemWidth = 10;
//                $item_array->ItemDepth = 10;
//                $item_array->WeightCat = "";
//                $item_array->Model = "";
//                $item_array->Category = 'dsdsda';
//                $item_array->Warehouse = $this->_warehouseNumber;
//                $item_array->AccountKey = $this->_accountKey;
//
//                $product_array[$i] = $item_array;
            }
            die;
//            $final_product_array = (object) array();
//            $final_product_array->ArticlesList = $product_array;
        }







    }

}
