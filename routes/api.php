<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('auth/install', 'Auth\ShopifyController@installShop')->name('auth.install');
Route::get('auth/callback', 'Auth\ShopifyController@processOAuthResultRedirect');
Route::post('webhooks/uninstalled', 'Auth\ShopifyController@handleAppUninstallation')->name('webhook.uninstalled');
//Route::post('webhooks/inventory_items/{slug}', 'InventoryController@handleInventoryItems')->name('webhook.inventory_items');
Route::post('webhooks/products/{slug}', 'ProductController@handleProducts')->name('webhook.products');
Route::post('webhooks/orders/{slug}', 'OrderController@handleOrders')->name('webhook.orders');
Route::get('webhooks/order/{id}', 'OrderController@updateOrderStatus');

Route::get('auth/check_webhook/{id}', 'Auth\ShopifyController@getWebhooks');