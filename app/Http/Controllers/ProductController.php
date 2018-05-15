<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use SoapClient;

class ProductController extends Controller {

    protected $_accountKey = '';
    protected $_warehouseNumber = '';
    protected $_client = null;
    protected $_user = null;

    /**
     * WarehouseSpace_Warehouse_Model_Api constructor.
     */
    public function __construct() {
        $this->_user = auth()->user();
        $user = $this->_user;
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

    public function handleProducts(Request $request, $slug) {
        Log::info('Products ' . $slug . ':' . json_encode($request->all()));
    }

    public function synchronizeProducts(Request $request) {
        $user = $this->_user;
        echo "<pre>";
        print_r($user);
        dd($this->_client);
        
    }

}
