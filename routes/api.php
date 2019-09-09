<?php

use Illuminate\Http\Request;

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

Route::namespace('Api')->group(function () {

    Route::group(['middleware' => 'ApiMicroService', 'prefix' => 'user'], function ($router) {
        Route::post('create-customer', 'CustomerController@createCustomer');
        Route::post('login', 'CustomerController@loginCustomer');
       
        Route::group(['middleware' => 'jwt.auth'], function ($router) {
            Route::post('detail', 'CustomerController@detailCustomer');
            Route::post('list', 'CustomerController@getAllCustomer');
            Route::post('logout', 'CustomerController@logoutCustomer');
            Route::post('delete', 'CustomerController@deleteCustomer');
            Route::post('update', 'CustomerController@updateCustomer');
        });
    });
});
