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

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'jwt.auth:api'], function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('me', 'AuthController@me');
    });
});


Route::group([
    'middleware' => 'jwt.auth:api',
    'prefix' => 'post',
    'namespace' => 'API'
], function () {

    Route::get('', 'PostController@index');
    Route::post('', 'PostController@store');
    Route::post('update', 'PostController@update');
    Route::post('imageUpload', 'PostController@uploadImage');
    Route::delete('{post}', 'PostController@destroy');
    Route::get('{post}', 'PostController@getPost');
});

Route::group([
    'middleware' => 'jwt.auth:api',
    'prefix' => 'user',
    'namespace' => 'API'
], function () {

    Route::get('', 'UserController@index');
    Route::post('', 'UserController@store');
    Route::put('{user}', 'UserController@update');
    Route::delete('{user}', 'UserController@destroy');
    Route::get('{user}', 'UserController@show');
});

Route::group([
    'middleware' => 'jwt.auth:api',
    'prefix' => 'setting',
    'namespace' => 'API'
], function () {

    Route::get('', 'SettingController@show');
    Route::post('', 'SettingController@store');
    Route::delete('', 'SettingController@destroy');
});


Route::group([
    'middleware' => 'jwt.auth:api',
    'prefix' => 'tag',
    'namespace' => 'API'
], function () {

    Route::get('', 'TagController@index');
    Route::post('', 'TagController@store');
    Route::put('{tag}', 'TagController@update');
    Route::delete('{tag}', 'TagController@destroy');
});