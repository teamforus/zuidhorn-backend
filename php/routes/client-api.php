<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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


Route::post('/api/voucher/{voucher_code}/activate', 'ClientApi\VoucherController@activate');
Route::get('/api/categories', 'ClientApi\CategoryController@index');

Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function() {
    Route::get('/user/voucher', 'ClientApi\VoucherController@target');
    Route::get('/user/voucher/qr-code', 'ClientApi\VoucherController@getQrCode');

    Route::get('/{any}', function ($any) {
        return response(['message' => 'Endpoint not found.'], 404);
    })->where('any', '.*');

    Route::get('/', function () {
        return response(['message' => 'Endpoint not found.'], 404);
    });
});