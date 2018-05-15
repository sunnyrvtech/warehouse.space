<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use SoapClient;
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
                    WarehouseSpace_Warehouse_Helper_Data::log('Soap client error: ' . $fault->getMessage());
                    //throw new Mage_Core_Exception('We could not connect to Warehouse.Space');
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
        
        $productinfo = $shopify->call(['URL' => 'products.json', 'METHOD' => 'GET']);
        dd($productinfo);
        
        
        

//        $parameters = (object) array();
//        $parameters->article = 214;
//        $parameters->description = 'ddddd';
//        $parameters->UOM = 'each';
//        $parameters->BuyPrice = 0;
//        $parameters->SellPrice = 12;
//        $parameters->Supplier = "";
//        $parameters->Images = "";
//
//        $parameters->Manufacturer = "";
//        $parameters->minQty = 10;
//        $parameters->itemWeight = 10;
//        $parameters->itemHeight = 10;
//        $parameters->itemWidth = 10;
//        $parameters->itemDepth = 10;
//        $parameters->weightCat = "";
//        $parameters->model = "";
//        $parameters->Category = 'dsdsda';
//        $parameters->warehouse = $this->_warehouseNumber;
//        $parameters->AccountKey = $this->_accountKey;
        
       
//        dd($client->__getFunctions());
        
//        $obj = $client->material($parameters);
//        dd($obj);
    }

}
