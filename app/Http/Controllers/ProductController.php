<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class ProductController extends Controller
{
    public function handleProducts(Request $request,$slug){
         Log::info('Products '.$slug.':'.json_encode($request->all()));
    }
}
