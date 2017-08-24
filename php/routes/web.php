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
Route::get('/device/approve/{device_approve_token}', function(Request $request, $device_approve_token) {
    if ($device_approve_token->status == 'approved')
        return 'You device is already approved!';
    
    $device_approve_token->update(['status' => 'approved']);

    return "Success!";
});

Route::group(['prefix' => 'panel', 'middleware' => 'auth'], function() {
    Route::get('/dashboard', 'Panel\DashboardController@getIndex');
    Route::get('/csv-parser', 'Panel\UsersController@getCsvParser');

    Route::get('/vouchers', 'Panel\VoucherController@getList');
    Route::get('/vouchers/view/{voucher}', 'Panel\VoucherController@getView');

    Route::get('/voucher-transactions/view/{voucher_transaction}', 'Panel\VoucherTransactionController@getView');

    Route::get('/citizens', 'Panel\UserController@getIndexCitizens');
    Route::get('/citizens/view/{user}', 'Panel\UserController@getViewCitizen');

    Route::resource('/categories', 'Panel\CategoryController');
    Route::get('/categories/{category}/destroy', 'Panel\CategoryController@destroy');

    Route::resource('/shop-keepers', 'Panel\ShopKeeperController');
    Route::get('/shop-keepers/{shopKeeper}/destroy', 'Panel\ShopKeeperController@destroy');
    Route::put('/shop-keepers/{shopKeeper}/state/approve', 'Panel\ShopKeeperController@stateApprove');

    Route::resource('/shop-keeper-categories', 'Panel\ShopKeeperCategoryController');
    Route::get('/shop-keeper-categories/{shopKeeperCategory}/destroy', 'Panel\ShopKeeperCategoryController@destroy');

    Route::resource('shop-keepers.offices', 'Panel\ShopKeeperOfficeController');
    Route::get('/shop-keepers/{shopKeeper}/offices/{office}/destroy', 'Panel\ShopKeeperOfficeController@destroy');

    Route::resource('/bugets', 'Panel\BugetController');
    Route::get('/bugets/{buget}/destroy', 'Panel\BugetController@destroy');

    Route::resource('/buget-categories', 'Panel\BugetCategoryController');
    Route::get('/buget-categories/{bugetCategory}/destroy', 'Panel\BugetCategoryController@destroy');

    Route::get('/users', 'Panel\UsersController@getAdminsList');
    Route::get('/permissions', 'Panel\UsersController@getPermissions');
});


Route::group(['prefix' => 'ajax', 'middleware' => 'auth'], function() {
    Route::get('/category/select-option', 'Ajax\CategoryController@getSelectOptions');
    Route::put('/buget/submit-data', 'Ajax\BugetController@putSubmitData');
});

Auth::routes();
