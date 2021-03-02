<?php

use Illuminate\Support\Facades\Route;

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
    return "<h1><a href='https://github.com/0xaliraza/wid-blog-backend'>Write It Down - Backend</a> is a headless CMS coded by <a href='https://0xali.com'>Ali Raza</a>.</h1>";
});
