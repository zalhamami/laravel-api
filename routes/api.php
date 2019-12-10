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

Route::group(['prefix' => 'type'], function () {
    Route::get('/', 'TypeController@index');
    Route::get('/{id}', 'TypeController@show');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', 'TypeController@store');
        Route::put('/{id}', 'TypeController@update');
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@signup');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::delete('logout', 'AuthController@logout');
    });
});

Route::group(['prefix' => 'me', 'middleware' => 'auth:api'], function () {
    Route::get('user', 'AuthController@user');
});
