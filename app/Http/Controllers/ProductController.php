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
                $wsdl = env('WSDL_MATERIAL_URL');
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
                    $item_array->Title = $request->get('title');
                    $item_array->Barcode = $item_value->barcode;
                    $item_array->Description = strip_tags($request->get('body_html'));
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
            $productinfo = $shopify->call(['URL' => 'products.json', 'METHOD' => 'GET']);
            $dom = new DOMDocument('1.0');
            $dom->formatOutput = true;
            $root = $dom->createElement('ArrayOfMaterialArticle');
            $dom->appendChild($root);
            for($i=1;$i<=1000;$i++){
            foreach ($productinfo->products as $key => $product) {
                $images = "";
                if ($product->images != null) {
                    $images = $dom->createElement('Images');
                    foreach ($product->images as $img) {
                        $images->appendChild($dom->createElement('string', $img->src));
                    }
                }

                foreach ($product->variants as $k=>$item_value) {
                    $items = $dom->createElement('MaterialArticle');
                    $items->appendChild($dom->createElement('AccountKey', $this->_accountKey));
                    $items->appendChild($dom->createElement('ProductID', $i));
                    if ($item_value->sku != "")
                        $items->appendChild($dom->createElement('Article', $item_value->sku));
                    $items->appendChild($dom->createElement('Title', $product->title));
                    if ($item_value->barcode != "")
                        $items->appendChild($dom->createElement('Barcode', $item_value->barcode));
                    $items->appendChild($dom->createElement('BuyPrice', $item_value->price));
                    if ($product->product_type != "")
                        $items->appendChild($dom->createElement('Category', $product->product_type));
                    if ($product->body_html != "")
                        $items->appendChild($dom->createElement('Description', strip_tags($product->body_html)));
//                    $items->appendChild($dom->createElement('ErpTimeStamp', ''));
//                    $items->appendChild($dom->createElement('TimeStamp', ''));
//                    $items->appendChild($dom->createElement('HSCode', ''));
                    if ($images != "")
                        $items->appendChild($images);
//                    $items->appendChild($dom->createElement('ItemDepth', ''));
//                    $items->appendChild($dom->createElement('ItemHeight', ''));
                    if ($item_value->weight != null)
                        $items->appendChild($dom->createElement('ItemWeight', $item_value->weight));
//                    $items->appendChild($dom->createElement('ItemWidth', ''));
//                    $items->appendChild($dom->createElement('Manufacturer', ''));
//                    $items->appendChild($dom->createElement('MinQuantity', ''));
//                    $items->appendChild($dom->createElement('Model', ''));
                    if ($item_value->compare_at_price != null)
                        $items->appendChild($dom->createElement('SellPrice', $item_value->compare_at_price));
//                    $items->appendChild($dom->createElement('Supplier', ''));
                    $items->appendChild($dom->createElement('UOM', 'each'));
                    $items->appendChild($dom->createElement('Warehouse', $this->_warehouseNumber));
//                    $items->appendChild($dom->createElement('WeightCat', ''));
                    $root->appendChild($items);
                    $i++;
                }
                
            }
            }
            echo $dom->getElementsByTagName('MaterialArticle')->length;
//            
//            die;
            
            echo '<xmp>' . $dom->saveXML() . '</xmp>';
            die;
//  $dom->save('result.xml') or die('XML Create Error');
            $tmpfile = tempnam(sys_get_temp_dir(), 'zip');
            rename($tmpfile, substr($tmpfile, 0, strlen($tmpfile) - 4) . '.zip');
            $tmpfile = substr($tmpfile, 0, strlen($tmpfile) - 4) . '.zip';
            $zip = new ZipArchive;
            $res = $zip->open($tmpfile, ZipArchive::OVERWRITE);
            if ($res === TRUE) {
                $zip->addFromString('Articles.xml', $dom->saveXML());
                $zip->close();
                $h = fopen($tmpfile, 'r');
                $file = fread($h, filesize($tmpfile));
                $zip_array = (object) array();
                $zip_array->data = $file;
                $result = $client->UploadProductsFile($zip_array);
                fclose($h);
                unset($file);
                unlink($tmpfile);
                echo htmlentities($client->__getLastRequest());
                dd($result);
            }
            die('ddd');
        }
        return redirect()->back()
                        ->with('error-message', 'Something went wrong,please try again later!');
    }

}
