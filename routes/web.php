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


Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('/followers', ['as' => 'followers', 'uses' => 'FollowersController@index']);
});

// Internal API
Route::group([
    'middleware' => 'auth',
    'namespace'  => 'InternalApi',
    'prefix'     => 'internal'
], function () {
    Route::get('followers', ['as' => 'internal-followers-api', 'uses' => 'FollowersController@index']);
    Route::get('followers/{id}', ['as' => 'internal-follower-api', 'uses' => 'FollowersController@show']);
});

Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::get('auth/500px', ['as' => 'auth-500px', 'uses' => 'Auth\AuthController@redirectToProvider']);
Route::get('auth/500px/callback', 'Auth\AuthController@handleProviderCallback');
