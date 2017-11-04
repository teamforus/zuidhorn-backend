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

Route::group(['prefix' => 'panel', 'middleware' => ['auth', 'panel']], function() {
    // testing
    Route::get('/test', 'TestController@getTest');
    
    Route::get('/dashboard', 'Panel\DashboardController@getIndex');
    Route::get('/csv-parser', 'Panel\UsersController@getCsvParser');

    Route::get('/vouchers', 'Panel\VoucherController@getList');
    Route::get('/vouchers/view/{voucher}', 'Panel\VoucherController@getView');

    Route::get('/voucher-transactions/view/{voucher_transaction}', 'Panel\TransactionController@getView');

    Route::get('/citizens', 'Panel\UserController@getIndexCitizens');
    Route::get('/citizens/view/{user}', 'Panel\UserController@getViewCitizen');

    Route::resource('/categories', 'Panel\CategoryController');
    Route::get('/categories/{category}/destroy', 'Panel\CategoryController@destroy');

    Route::resource('/shop-keepers', 'Panel\ShopKeeperController');
    Route::get('/shop-keepers/{shopKeeper}/destroy', 'Panel\ShopKeeperController@destroy');
    Route::put('/shop-keepers/{shopKeeper}/state/approve', 'Panel\ShopKeeperController@stateApprove');

    Route::resource('/shop-keeper-categories', 'Panel\ShopKeeperCategoryController');
    Route::get('/shop-keeper-categories/{shopKeeperCategory}/destroy', 'Panel\ShopKeeperCategoryController@destroy');

    Route::resource('shop-keepers.offices', 'Panel\OfficeController');
    Route::get('/shop-keepers/{shopKeeper}/offices/{office}/destroy', 'Panel\OfficeController@destroy');

    Route::resource('/budgets', 'Panel\BudgetController');
    Route::get('/budgets/{budget}/destroy', 'Panel\BudgetController@destroy');

    Route::resource('/budget-categories', 'Panel\BudgetCategoryController');
    Route::get('/budget-categories/{budgetCategory}/destroy', 'Panel\BudgetCategoryController@destroy');

    Route::get('/users', 'Panel\UsersController@getAdminsList');
    Route::get('/permissions', 'Panel\UsersController@getPermissions');
});


Route::group(['prefix' => 'ajax', 'middleware' => ['auth', 'panel']], function() {
    Route::get('/category/select-option', 'Ajax\CategoryController@getSelectOptions');
    Route::put('/budget/submit-data', 'Ajax\BudgetController@putSubmitData');
});

Auth::routes();
