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

Route::group(['prefix' => 'client'], function() {
    Route::post('/voucher/{voucher_code}/activate', 'ClientApi\VoucherController@activate');
    Route::get('/categories', 'ClientApi\CategoryController@index');
    Route::post('/contact-form', 'ClientApi\ContactController@postIndex');
});

Route::group(['prefix' => 'client', 'middleware' => 'auth:api'], function() {
    Route::get('/user/voucher', 'ClientApi\VoucherController@target');
    Route::get('/user/voucher/qr-code', 'ClientApi\VoucherController@getQrCode');
    Route::post('/user/voucher/email', 'ClientApi\VoucherController@sendQrCodeEmail');

    Route::resource('/user/voucher/transactions', 'ClientApi\Vouchers\TransactionsController');

    Route::get('/{any}', function ($any) {
        return response(['message' => 'Endpoint not found.'], 404);
    })->where('any', '.*');

    Route::get('/', function () {
        return response(['message' => 'Endpoint not found.'], 404);
    });
});