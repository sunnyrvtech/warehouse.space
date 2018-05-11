<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class OrderController extends Controller
{
    public function handleOrders(Request $request,$slug){
         Log::info('Orders '.$slug.':'.json_encode($request->all()));
    }
}
