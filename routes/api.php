<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiPingController;
use App\Http\Controllers\Api\ApiReleaseController;

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

Route::post('ping', [ApiPingController::class, 'ping']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::name('api.')->group(function () {
        Route::apiResource('api/releases', ApiReleaseController::class)->only([
            'index', 'store'
        ]);
    });
});
