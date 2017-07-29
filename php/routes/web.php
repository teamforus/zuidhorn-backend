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

Route::get('/home', function () {
    return redirect(action('Panel\DashboardController@getIndex'));
});

Route::get('/', function () {
    return redirect(action('Panel\DashboardController@getIndex'));
});

Route::get('/test', 'TestController@getTest');

Route::group(['prefix' => 'panel', 'middleware' => 'auth'], function() {
    Route::get('/dashboard', 'Panel\DashboardController@getIndex');
    Route::get('/csv-parser', 'Panel\UsersController@getCsvParser');


    Route::get('/categories', 'Panel\CategoryController@getIndex');
    Route::get('/categories/edit/{category?}', 'Panel\CategoryController@getEdit');
    Route::get('/categories/create', 'Panel\CategoryController@getCreate');
    Route::get('/categories/view/{category}', 'Panel\CategoryController@getView');
    Route::get('/categories/delete/{category}', 'Panel\CategoryController@getDelete');

    Route::put('/categories/edit/{category}', 'Panel\CategoryController@putEdit');
    Route::put('/categories/create', 'Panel\CategoryController@putCreate');


    Route::get('/vouchers', 'Panel\VoucherController@getList');
    Route::get('/vouchers/view/{voucher}', 'Panel\VoucherController@getView');

    Route::get('/voucher-transactions/view/{voucher_transaction}', 'Panel\VoucherTransactionController@getView');

    Route::get('/citizens', 'Panel\UserController@getIndexCitizens');
    Route::get('/citizens/view/{user}', 'Panel\UserController@getViewCitizen');

    Route::get('/shopers', 'Panel\ShopersController@getList');
    Route::get('/bugets', 'Panel\BugetsController@getList');
    Route::get('/users', 'Panel\UsersController@getAdminsList');
    Route::get('/permissions', 'Panel\UsersController@getPermissions');
});


Route::group(['prefix' => 'ajax', 'middleware' => 'auth'], function() {
    Route::get('/category/select-option', 'Ajax\CategoryController@getSelectOptions');
    Route::put('/buget/submit-data', 'Ajax\BugetController@putSubmitData');
});

Route::group(['prefix' => 'app', 'middleware' => 'cors'], function() {
    // Route::get('/vouchers/{voucher_code}', 'App\VoucherController@getVoucherByCode');

    Route::resource('voucher', 'App\VoucherController', [
        'parameters' => ['voucher' => 'voucher_code'],
        "only" => ['show', 'update']
        ]);
});

Auth::routes();
