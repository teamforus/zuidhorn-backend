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

Route::post('/shop-keepers/sign-up', 'Api\ShopKeeperController@signUp');
Route::get('/shop-keepers/devices/token', 'Api\ShopKeeperController@createDeviceToken');
Route::get('/shop-keepers/devices/token/{device_token}/state', 'Api\ShopKeeperController@getDeviceTokenState');

Route::group(['prefix' => '', 'middleware' => 'auth:api'], function() {
    Route::post('/shop-keepers/devices/token/{device_token}', 'Api\ShopKeeperController@authorizeDeviceToken');

    Route::get('/user', 'Api\UserController@curentUser');
    Route::get('/user/revoke-token', 'Api\UserController@revokeToken');

    Route::post('/vouchers/{voucher_public_key}/transactions/{transaction}/refund', 'Api\Voucher\TransactionController@refund');
    Route::resource('vouchers.transactions', 'Api\Voucher\TransactionController', [
        'parameters' => ['vouchers' => 'voucher_public_key']
    ]);
    Route::resource('vouchers', 'Api\VoucherController', [
        'parameters' => ['vouchers' => 'voucher_public_key']
    ]);

    Route::get('/shop-keepers/{shop_keeper}/categories', 'Api\ShopKeeperController@categories');
    Route::put('/shop-keepers/{shop_keeper}/image', 'Api\ShopKeeperController@updateImage');
    Route::resource('/shop-keepers', 'Api\ShopKeeperController');

    Route::put('/offices/{office}/image', 'Api\OfficeController@updateImage');
    Route::get('/offices/count', 'Api\OfficeController@count');
    Route::resource('/offices', 'Api\OfficeController');

    Route::get('/transactions/count', 'Api\TransactionController@count');
    Route::resource('/transactions', 'Api\TransactionController');

    Route::resource('/categories', 'Api\CategoryController');

    Route::get('/refund/amount', 'Api\RefundController@amount');
    Route::get('/refund/link', 'Api\RefundController@link');

    Route::get('/status', function() {
        return response(["status" => "operational"]);
    });

    Route::get('/{any}', function ($any) {
        return response(['message' => 'Endpoint not found.'], 404);
    })->where('any', '.*');

    Route::get('/', function () {
        return response(['message' => 'Endpoint not found.'], 404);
    });
});