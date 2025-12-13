<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController;

Route::post('login', [LoginController::class, 'login']);
Route::post('register-user', [UserController::class, 'storeUser']);
Route::post('register-support', [UserController::class, 'storeSupport']);


Route::middleware(\App\Http\Middleware\CustomAuthenticate::class)->group(function () {
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('areas', AreaController::class);
    Route::apiResource('roles', RoleController::class)->only(['index','show']);
    Route::apiResource('ticket-statuses', TicketStatusController::class)->only(['index','show']);
    Route::post('logout', [LoginController::class, 'logout']);
});