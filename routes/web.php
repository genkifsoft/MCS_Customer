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
    return view('admin.dashboard');
});


Route::post('auth/social', 'SocialAuthController@authSocial');

Route::group(['prefix' => 'auth'], function () {
    Route::get('create-customer', 'CustomerController@getCreateCustomer');
    Route::post('create-customer', 'CustomerController@createCustomer')->name('create_customer');
    Route::post('login', 'CustomerController@loginCustomer');
    Route::post('fogot-password', 'CustomerController@fogotPassword');

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::post('detail', 'CustomerController@detailCustomer');
        Route::post('list', 'CustomerController@getAllCustomer');
        Route::post('logout', 'CustomerController@logoutCustomer');
        Route::post('delete', 'CustomerController@deleteCustomer');
        Route::post('update', 'CustomerController@updateCustomer');
        Route::post('refresh', 'CustomerController@refreshCustomer');
        Route::post('change-pass', 'CustomerController@changePassword');
    });
});