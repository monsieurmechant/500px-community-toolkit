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

// Home
Route::get('/', function () {
    return redirect()->route('home');
})->middleware('auth');

// Auth
Route::get('login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::get('auth/500px', ['as' => 'auth-500px', 'uses' => 'Auth\AuthController@redirectToProvider']);
Route::get('auth/500px/callback', 'Auth\AuthController@handleProviderCallback');

// JS SPA
Route::group(['middleware' => 'auth', 'prefix' => 'app'], function () {
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
});

// Internal API
Route::group([
    'middleware' => 'auth',
    'namespace'  => 'InternalApi',
    'prefix'     => 'internal'
], function () {
    Route::get('followers', ['as' => 'internal-followers-api', 'uses' => 'FollowersController@index']);
    Route::get('followers/{id}', ['as' => 'internal-follower-api', 'uses' => 'FollowersController@show']);
    Route::resource('photos', 'PhotosController', [
        'only'  => [
            'index',
            'show',
            'update',
        ],
        'names' => [
            'index' => 'internal-photos-api',
            'show'  => 'internal-photo-api',
        ]
    ]);
    Route::resource('comments', 'CommentsController', [
        'only'  => [
            'index',
            'show',
            'update',
            'store',
        ],
        'names' => [
            'index' => 'internal-comments-api',
        ]
    ]);

});
