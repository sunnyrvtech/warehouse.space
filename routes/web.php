<?php

use App\User;

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
    
    $user = User::first();
                    auth()->login($user);
    
    return view('welcome');
});

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'Auth\ShopifyController@index')->name('dashboard');
    Route::get('warehouse/setting', 'Auth\ShopifyController@warehouseSetting')->name('warehouse.setting');
});
Route::group(['prefix' => 'admin_warehouse','middleware' => 'IsAdmin'], function () {
   Route::get('/', 'Admin\IndexController@index')->name('admin');
   Route::resource('users', 'Admin\UserController');
});


