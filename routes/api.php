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
    Route::post('create', 'PostController@store');
    Route::post('update', 'PostController@update');
    Route::post('imageUpload', 'PostController@uploadImage');
    Route::get('tags', 'PostController@indexTags');
    Route::delete('{post}', 'PostController@destroy');
    Route::get('{post}', 'PostController@getPost');
});

Route::group([
    'middleware' => 'jwt.auth:api',
    'prefix' => 'user',
    'namespace' => 'API'
], function () {

    Route::get('', 'UserController@index');
    Route::post('create', 'UserController@store');
    Route::put('{user}', 'UserController@update');
    Route::delete('{user}', 'UserController@destroy');
    Route::get('{user}', 'UserController@show');
});
