<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'municipality', 'middleware' => ['auth:api', 'municipality.api']], function() {
    // get list available categories
    Route::get('categories', 'MunicipalityApi\CategoryController@index');

    // User related routes
    Route::group(['prefix' => 'user'], function() {
        // get user details
        Route::get('/', 'MunicipalityApi\UserController@user');

        // get funds available on bunq
        Route::get('/funds', 'MunicipalityApi\UserController@funds');
    });

    // Budget related routes
    Route::group(['prefix' => 'budget'], function() {
        // get budget details
        Route::get('/', 'MunicipalityApi\BudgetController@show');

        // update budget settings
        Route::put('/', 'MunicipalityApi\BudgetController@update');

        // upload budget csv
        Route::post('/csv', 'MunicipalityApi\BudgetController@csv');

        // fetch voucher states by activation codes
        Route::post('/voucher-state', 'MunicipalityApi\BudgetController@voucherState');

        // upload budget csv
        Route::post('/add-children', 'MunicipalityApi\BudgetController@addChildren');
    });

    // Shopkeepers related routes
    Route::group(['prefix' => 'shop-keepers'], function() {
        // change shopkeeper state
        Route::post('/{shopKeeper}/state', 'MunicipalityApi\ShopKeeperController@state');

        // list shopkeepers
        Route::get('/', 'MunicipalityApi\ShopKeeperController@index');
    });

    // Earnings related routes
    Route::group(['prefix' => 'earnings'], function() {
        // shopkeeper earnings/debs
        Route::get('/shop-keepers', 'MunicipalityApi\ShopKeeperController@earnings');

        // category earnings
        Route::get('/categories', 'MunicipalityApi\CategoryController@earnings');
    });
});