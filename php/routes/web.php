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

Route::get('/', function () {
    return App\Models\User::find(3)->vouchers;
    return view('welcome');
});


Route::group(['prefix' => 'panel'], function() {
    Route::get('/dashboard', 'Panel\DashboardController@getIndex');
    Route::get('/csv-parser', 'Panel\UsersController@getCsvParser');
    Route::get('/categories', 'Panel\CategoriesController@getList');
    Route::get('/categories/edit/{category?}', 'Panel\CategoriesController@getEdit');
    Route::get('/citizens', 'Panel\CitizensController@getList');
    Route::get('/shopers', 'Panel\ShopersController@getList');
    Route::get('/bugets', 'Panel\BugetsController@getList');
    Route::get('/users', 'Panel\UsersController@getAdminsList');
    Route::get('/permissions', 'Panel\UsersController@getPermissions');
});