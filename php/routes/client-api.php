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

// public routes
Route::group(['prefix' => 'client'], function() {
    // send activation token to email
    Route::post(
        '/voucher/{voucher_code}/activate', 
        'ClientApi\VoucherController@activateByEmail');

    // activate the voucher by token received from email
    Route::post(
        '/voucher/activate-token', 
        'ClientApi\VoucherController@activateToken');




    // send auth token to email
    Route::post(
        '/user/send-token', 
        'ClientApi\CitizenController@sendAuthToken');

    // exchange the auth token to the access token
    Route::post(
        '/user/sign-in', 
        'ClientApi\CitizenController@signIn');




    // get all available categories
    Route::get(
        '/categories', 
        'ClientApi\CategoryController@index');




    // the contact form
    Route::post(
        '/contact-form', 
        'ClientApi\ContactController@postIndex');
});

// protected routes
Route::group(['prefix' => 'client', 'middleware' => ['auth:api', 'client.api']], function() {
    // citizen voucher details
    Route::get('/user/voucher', 'ClientApi\VoucherController@target');

    // citizen voucher qr-code
    Route::get('/user/voucher/qr-code', 'ClientApi\VoucherController@getQrCode');
    
    // send qr-code to citizen email
    Route::post('/user/voucher/email', 'ClientApi\VoucherController@sendQrCodeEmail');




    // display a listing of the transactions
    Route::get('/user/voucher/transactions', 'ClientApi\Vouchers\TransactionsController@index');
});

// 404 route
Route::get('/client/{any}', function ($any) {
    return response(['message' => 'Endpoint not found.'], 404);
})->where('any', '.*');

// 404 route
Route::get('/client/', function () {
    return response(['message' => 'Endpoint not found.'], 404);
});