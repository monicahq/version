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

if (App::environment('production')) {
    URL::forceScheme('https');
}

Route::get('/', function () {
    return view('welcome');
});

Route::post('ping/', 'PingController@ping');

Route::group(['middleware' => 'throttle'], function () {
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/releases', 'HomeController@releases');
Route::get('/releases/add', 'HomeController@releaseAdd');
Route::post('/releases/add', 'HomeController@releaseStore');
