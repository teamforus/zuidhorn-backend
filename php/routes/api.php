<?php

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

// sign up
Route::post(
    '/shop-keepers/sign-up', 
    'Api\ShopKeeperController@signUp');

// generate device auth tokens
Route::get(
    '/shop-keepers/devices/token', 
    'Api\ShopKeeperController@createDeviceToken');

// check device auth tokens
Route::get(
    '/shop-keepers/devices/token/{device_token}/state', 
    'Api\ShopKeeperController@getDeviceTokenState');




Route::group([
    'prefix' => '', 
    'middleware' => ['auth:api', 'shopkeeper.api']
], function() {

    // authorize device auth tokens
    Route::post(
        '/shop-keepers/devices/token/{device_token}', 
        'Api\ShopKeeperController@authorizeDeviceToken');

    // get shopkeepers categories
    Route::get(
        '/shop-keepers/categories', 
        'Api\ShopKeeperController@categories');

    // upload shopkeeper's photo
    Route::put(
        '/shop-keepers/image', 
        'Api\ShopKeeperController@updateImage');

    // view and update shopkeeper
    Route::get(
        '/shop-keepers', 
        'Api\ShopKeeperController@show');
    Route::put(
        '/shop-keepers', 
        'Api\ShopKeeperController@update');




    // view user, revoke token
    Route::get('/user', 'Api\UserController@curentUser');
    Route::get('/user/revoke-token', 'Api\UserController@revokeToken');




    // mark transaction to be refunded
    Route::post(
        '/vouchers/{voucherAddress}/transactions/{transaction}/refund', 
        'Api\Vouchers\TransactionController@refund');
    
    // mark transaction to be refunded
    Route::resource(
        'vouchers.transactions', 
        'Api\Vouchers\TransactionController', [
            'parameters'    => ['vouchers' => 'voucherAddress'],
            'only'          => ['index', 'store', 'show']
        ]);




    // get voucher details
    Route::resource(
        'vouchers', 
        'Api\VoucherController', [
            'parameters'    => ['vouchers' => 'voucherAddress'],
            'only'          => ['show']
        ]);




    // upload office image
    Route::put(
        '/offices/{office}/image', 
        'Api\OfficeController@updateImage');

    // get number of owned offices
    Route::get(
        '/offices/count', 
        'Api\OfficeController@count');

    // list, view, update and delete shopkeeper's offices
    Route::resource(
        '/offices', 
        'Api\OfficeController', [
            'only'          => ['index', 'store', 'show', 'update', 'destroy']
        ]);




    // number of all shopkeeper's transactions
    Route::get(
        '/transactions/count', 
        'Api\TransactionController@count');

    // list of all shopkeeper's transactions
    Route::get(
        '/transactions', 
        'Api\TransactionController@index');




    // get refund amount
    Route::get(
        '/refund/amount', 
        'Api\RefundController@amount');
    
    // get refund link (from bunq)
    Route::get(
        '/refund/link', 
        'Api\RefundController@link');




    // list all available categories
    Route::get(
        '/categories', 
        'Api\CategoryController@index');




    // return api status
    Route::get('/status', function() {
        return response(["status" => "operational"]);
    });

    // 404 response
    Route::get('/{any}', function ($any = false) {
        return response(['message' => 'Endpoint not found.'], 404);
    })->where('any', '.*');

    // 404 response
    Route::get('/', function () {
        return response(['message' => 'Endpoint not found.'], 404);
    });
});