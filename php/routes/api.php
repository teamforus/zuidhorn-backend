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

Route::post('/shop-keeper/sign-up', 'Api\ShopKeeperController@signUp');

Route::group(['prefix' => '', 'middleware' => 'auth:api'], function() {
    Route::get(
        '/user', 
        'Api\UserController@curentUser');

    Route::resource(
        '/vouchers', 
        'Api\VoucherController', 
        ['parameters' => ['vouchers' => 'voucher_code']]);

    Route::resource(
        '/shop-keepers', 
        'Api\ShopKeeperController');

    Route::get('/status', function() {
        return response(["status" => "operational"]);
    });

    Route::get('/{any}', function ($any) {
        return response(['message' => 'Endpoint not found.'], 404);
    })->where('any', '.*');
});