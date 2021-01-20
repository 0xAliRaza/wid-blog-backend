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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::group(['middleware' => 'jwt.auth:api'], function () {
        Route::post('logout', 'Auth\AuthController@logout');
        Route::post('me', 'Auth\AuthController@me');
    });
});


Route::group([
    'middleware' => 'jwt.auth:api',
    'prefix' => 'post'
], function () {

    Route::get('', 'API\PostController@index');
    Route::post('create', 'API\PostController@store');
    Route::post('update', 'API\PostController@update');
    Route::post('imageUpload', 'API\PostController@uploadImage');
    Route::get('tags', 'API\PostController@indexTags');
    Route::delete('{post}', 'API\PostController@destroy');
    Route::get('{post}', 'API\PostController@getPost');
});
