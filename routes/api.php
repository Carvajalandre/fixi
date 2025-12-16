<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InteractionController;

Route::post('login', [LoginController::class, 'login']);
Route::post('register-user', [UserController::class, 'storeUser']);
Route::post('register-support', [UserController::class, 'storeSupport']);


Route::middleware(\App\Http\Middleware\CustomAuthenticate::class)->group(function () {
    Route::get('me', function (Request $request) {
        return $request->user()->load('role','area');
    });

    Route::post('tickets/{id}/assign', [TicketController::class, 'assign']);
    Route::get('tickets/{ticketId}/interactions', [InteractionController::class, 'index']);
    Route::post('interactions', [InteractionController::class, 'store']);
    Route::get('interactions/{id}', [InteractionController::class, 'show']);
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('areas', AreaController::class);
    Route::apiResource('roles', RoleController::class)->only(['index','show']);
    Route::apiResource('ticket-statuses', TicketStatusController::class)->only(['index','show']);
    Route::post('logout', [LoginController::class, 'logout']);
});