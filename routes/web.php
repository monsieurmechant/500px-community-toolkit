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


Route::group(['middleware' => 'auth'], function() {
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
    Route::get('/',  ['as' => 'home', 'uses' => 'HomeController@index']);
});

Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::get('auth/500px', ['as' => 'auth-500px', 'uses' => 'Auth\AuthController@redirectToProvider']);
Route::get('auth/500px/callback', 'Auth\AuthController@handleProviderCallback');
