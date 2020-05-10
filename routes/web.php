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

Route::redirect('/', '/login', 301);

Auth::routes();

Route::get('/home', 'LinkController@index')->name('home');

Route::get("/v1/{token}", 'HomeController@index')->name("view-url");

Route::prefix("/account")->group(function(){
    Route::get("/", "PerfilController@index")->name("miperfil");
    Route::get("/view/{id}", "PerfilController@show");
    Route::put('/edit/{id}', "PerfilController@update");
});

Route::resource('links', 'LinkController');
