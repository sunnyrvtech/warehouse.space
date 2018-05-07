<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function handleInventory(Request $request){
         Log::info('Inventory:'.json_decode($requst->all()));
    }
}
