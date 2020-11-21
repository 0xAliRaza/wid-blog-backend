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
    // Middleware is assigned in the Controller...
    Route::post('login', 'Auth\AuthController@login');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('me', 'Auth\AuthController@me');
});


Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'post'
], function () {

    Route::get('', 'API\PostController@index');
    Route::post('create', 'API\PostController@store');
    Route::put('update', 'API\PostController@update');
    Route::delete('delete', 'API\PostController@destroy');
    Route::post('imageUpload', 'API\PostController@uploadImage');
    Route::post('tags', 'API\PostController@indexTags');
});
