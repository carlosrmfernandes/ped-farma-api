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


Route::get('/', function () {
    echo "api rodando";
});

Route::group(['middleware' => ['apiJwt'], 'prefix' => 'auth',], function ($router) {
    Route::post('logout', 'AuthController@logout');
    Route::get('user', 'UserController@index');
    Route::get('user-view/{id}', 'UserController@show')->middleware('chekUser');
    Route::post('user-update/{id}', 'UserController@update')->middleware('chekUser');
    Route::post('user-delete/{id}', 'UserController@destroy')->middleware('chekUser');
    Route::post('product', 'ProductController@store')->middleware('checkProvider');
    Route::get('product-view/{id}', 'ProductController@show');
    Route::resource('sale', 'SaleController');    
    Route::resource('favorite', 'FavoriteProductController')->middleware('checkCustomer');
});


Route::group(['prefix' => ''], function ($router) {
    Route::post('register-user', 'UserController@store');
    Route::post('login', 'AuthController@login');
    Route::get('product', 'ProductController@index');
});
