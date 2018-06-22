<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use SoapClient;
use SoapFault;
use App\User;
use App;
use ZipArchive;
use DOMDocument;

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
            $route_name = $request->route()->getName();
            $this->_user = auth()->user();
            $user = $this->_user;
            if (isset($user->get_dev_setting)) {
                $this->_shopify = App::makeWith('ShopifyAPI', ['API_KEY' => env('SHOPIFY_APP_KEY'), 'API_SECRET' => env('SHOPIFY_APP_SECRET'), 'SHOP_DOMAIN' => $user->shop_url, 'ACCESS_TOKEN' => $user->access_token]);
                $this->_accountKey = $user->get_dev_setting->account_key;
                $this->_warehouseNumber = $user->get_dev_setting->warehouse_number;
            }
            $debug = true;
            if ($route_name == "warehouse.product.sync")
                $wsdl = env('WSDL_URL');
            else
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
            return $next($request);
        });
    }

    public function handleProducts(Request $request, $slug) {
        $client = $this->_client;
        $shopUrl = $request->headers->get('x-shopify-shop-domain');
        if ($client != null && ($slug == "create" || $slug == "update")) {
            $user = User::Where('shop_url', $shopUrl)->first();
            if (isset($user->get_dev_setting)) {
                $product_images = array_column($request->get('images'), 'src');
                $i = 0;
                $product_array = array();
                foreach ($request->get('variants') as $item_value) {
                    $item_value = (object) $item_value;
                    $item_array = (object) array();
                    $item_array->ProductID = $item_value->id;
                    $item_array->Article = $item_value->sku;
                    $item_array->Title = htmlspecialchars($request->get('title'));
                    $item_array->Barcode = $item_value->barcode;
                    $item_array->Description = htmlspecialchars(strip_tags($request->get('body_html')));
//                    $item_array->ErpTimeStamp = date('Y-m-d-H:i');
//                    $item_array->TimeStamp = date('Y-m-d-H:i');
                    $item_array->HSCode = "";
                    $item_array->UOM = 'each';
                    $item_array->BuyPrice = $item_value->price;
                    $item_array->SellPrice = $item_value->compare_at_price;
                    $item_array->Supplier = "";
                    $item_array->Images = $product_images;
                    $item_array->Manufacturer = "";
                    $item_array->MinQuantity = 0;
                    $item_array->ItemWeight = $item_value->weight;
                    $item_array->ItemHeight = 0;
                    $item_array->ItemWidth = 0;
                    $item_array->ItemDepth = 0;
                    $item_array->WeightCat = 0;
                    $item_array->Model = "";
                    $item_array->Category = $request->get('product_type');
                    $item_array->Warehouse = $user->get_dev_setting->warehouse_number;
                    $item_array->AccountKey = $user->get_dev_setting->account_key;

                    $product_array[$i] = $item_array;
                    $i++;
                }

                $final_product_array = (object) array();
                $final_product_array->ArticlesList = $product_array;

                $result = $client->MaterialBulk($final_product_array);
                Log::info($shopUrl . ' Product ' . $slug . $result->MaterialBulkResult);
                exit();
            }
            Log::info($shopUrl . ' Product ' . $slug . 'not saved account setting yet !');
            exit();
        } else {
            if ($slug != "delete")
                Log::info($shopUrl . ' Product ' . $slug . 'problem in soap client !');
            exit();
        }
    }

    public function synchronizeProducts(Request $request) {
         $user = $this->_user;
        $client = $this->_client;
        $shopify = $this->_shopify;
        if ($client != null && $shopify != null) {
//            $limit = $user->get_dev_setting->page_size;
//            $page = $user->get_dev_setting->offset;
            $productinfo = $shopify->call(['URL' => 'products.json', 'METHOD' => 'GET']);
            $i = 0;

            $product_array = array();
            foreach ($productinfo->products as $key => $product) {
                $product_images = array_column($product->images, 'src');

                foreach ($product->variants as $item_value) {

//                    $hscode = $shopify->call(['URL' => 'products/'.$product->id.'/variants/'.$item_value->id.'/metafields.json', 'METHOD' => 'GET']);

                    $item_array = (object) array();
                    $item_array->ProductID = $item_value->id;
                    $item_array->Article = $item_value->sku;
                    $item_array->Title = $product->title;
                    $item_array->Barcode = $item_value->barcode;
                    $item_array->Description = strip_tags($product->body_html);
                    $item_array->ErpTimeStamp = date('Y-m-d-H:i');
                    $item_array->TimeStamp = date('Y-m-d-H:i');
                    $item_array->HSCode = "";
                    $item_array->UOM = 'each';
                    $item_array->BuyPrice = $item_value->price;
                    $item_array->SellPrice = $item_value->compare_at_price;
                    $item_array->Supplier = "";
                    $item_array->Images = $product_images;
                    $item_array->Manufacturer = "";
                    $item_array->MinQuantity = 0;
                    $item_array->ItemWeight = $item_value->weight;
                    $item_array->ItemHeight = 0;
                    $item_array->ItemWidth = 0;
                    $item_array->ItemDepth = 0;
                    $item_array->WeightCat = 0;
                    $item_array->Model = "";
                    $item_array->Category = $product->product_type;
                    $item_array->Warehouse = $this->_warehouseNumber;
                    $item_array->AccountKey = $this->_accountKey;

                    $product_array[$i] = $item_array;
                    $i++;
                }
            }

            $final_product_array = (object) array();
            $final_product_array->ArticlesList = $product_array;

            $result = $client->MaterialBulk($final_product_array);
            if ($result->MaterialBulkResult)
                return redirect()->back()
                                ->with('success-message', 'Product synchronization completed successfully!');
            else
                return redirect()->back()
                                ->with('error-message', 'Something went wrong,please try again later!');
        }
        return redirect()->back()
                        ->with('error-message', 'Something went wrong,please try again later!');
        
                }

}
