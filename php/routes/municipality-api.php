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


Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function() {
    Route::get('user', 'MunicipalityApi\UserController@user');

    Route::get('buget', 'MunicipalityApi\BugetController@get');
    Route::put('buget', 'MunicipalityApi\BugetController@update');
    Route::post('buget/csv', 'MunicipalityApi\BugetController@csv');

    Route::resource('categories', 'MunicipalityApi\CategoryController');

    Route::post('/shop-keepers/{shopKeeper}/state', 'MunicipalityApi\ShopKeeperController@state');
    Route::resource('shop-keepers', 'MunicipalityApi\ShopKeeperController');
    Route::resource('shop-keepers.offices', 'MunicipalityApi\ShopKeeper\OfficeController');
});

Auth::routes(); 