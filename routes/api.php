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

Route::group([
    'middleware' => 'auth:api'
], function () {
    Route::resource('/provider', 'ProviderController')->except(['edit', 'create']);

    Route::resource('/product', 'ProductController')->except(['edit', 'create']);
    Route::resource('/category', 'CategoryController')->except(['edit', 'create']);

    Route::get('/payment/cash', 'PaymentController@cash');
    Route::get('/payment/check', 'PaymentController@check');
    Route::resource('/payment', 'PaymentController')->except(['edit', 'create']);


    Route::get('/bill/search', 'BillController@search');
    Route::get('/bill/no-payment', 'BillController@noPayment');
    Route::resource('/bill', 'BillController')->except(['edit', 'create']);


    Route::get('/depot/bank', 'DepotController@bank');
    Route::get('/depot/box', 'DepotController@box');
    Route::resource('/depot', 'DepotController')->except(['edit', 'create']);

    Route::resource('/user', 'UserController')->except(['edit', 'create'])->middleware('admin');
    Route::resource('/role', 'RoleController')->only(['index'])->middleware('admin');

    Route::get('/history/bank', 'HistoryController@bank');
    Route::get('/history/box', 'HistoryController@box');
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});