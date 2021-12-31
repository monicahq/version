<?php

use App\Http\Controllers\ChartsController;
use App\Http\Controllers\ReleaseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('releases', ReleaseController::class)->only([
        'index', 'create', 'store', 'update', 'destroy',
    ]);
    Route::resource('charts', ChartsController::class)->only([
        'index'
    ]);
});
