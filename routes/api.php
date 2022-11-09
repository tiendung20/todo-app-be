<?php

use Illuminate\Http\Request;
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

Route::prefix('v1')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::apiResource('tasks', \App\Http\Controllers\TaskController::class);
        Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
    Route::apiResource('users', \App\Http\Controllers\UserController::class);
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'authenticate']);
});
