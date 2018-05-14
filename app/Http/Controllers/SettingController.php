<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ApiSetting;
use App\DeveloperSetting;

class SettingController extends Controller {

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

        ApiSetting::create($data);
        return redirect()->back()
                        ->with('success-message', 'Api setting saved successfully!');
    }

    public function devPostSetting(Request $request) {
        $data = $request->all();
        $this->validate($request, [
            'wsdl_url' => 'required|max:50',
            'percentage_product' => 'required|max:50',
            'page_size' => 'required|digits_between:1,10',
            'offset' => 'required|digits_between:1,10',
        ]);

        $data['user_id'] = auth()->id();

        DeveloperSetting::create($data);
        if (!ApiSetting::Where('user_id', auth()->id())->first())
            ApiSetting::create($data);
        return redirect()->back()
                        ->with('success-message', 'Developer setting saved successfully!');
    }

}
