<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TokensController;
use App\Http\Controllers\ReleaseController;

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
    return view('welcome');
});

Route::post('ping/', [PingController::class, 'ping']);

Route::group(['middleware' => 'throttle'], function () {
});

Auth::routes();

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('releases', ReleaseController::class)->only([
        'index', 'create', 'store'
    ]);

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('tokens', TokensController::class)->only([
        'index', 'create', 'store', 'destroy'
    ]);

});
