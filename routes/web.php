<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return view('welcome');
});

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'Auth\ShopifyController@index')->name('dashboard');
    Route::get('warehouse/setting', 'SettingController@warehouseSetting')->name('warehouse.setting');
    Route::post('warehouse/api/setting', 'SettingController@apiPostSetting')->name('warehouse.api.setting');
    Route::post('warehouse/dev/setting', 'SettingController@devPostSetting')->name('warehouse.dev.setting');
    Route::get('warehouse/product/sync', 'ProductController@synchronizeProducts')->name('warehouse.product.sync');
});

Route::get('aunthenticate/{shop_url}','Auth\ShopifyController@storeAuthenticate')->name('authenticate');

Route::group(['prefix' => 'admin_warehouse','middleware' => 'IsAdmin'], function () {
   Route::get('/', 'Admin\IndexController@index')->name('admin');
   Route::resource('users', 'Admin\UserController');
   Route::get('customer/login/{id}', 'Admin\IndexController@customerLogin')->name('customer.login');
});


