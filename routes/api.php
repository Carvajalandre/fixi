<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\TicketController;

// Ruta de ejemplo de usuario autenticado
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas API de tu aplicación
Route::apiResource('areas', AreaController::class);
Route::apiResource('roles', RoleController::class)->only(['index','show']);
Route::apiResource('users', UserController::class);
Route::apiResource('ticket-statuses', TicketStatusController::class)->only(['index','show']);
Route::apiResource('tickets', TicketController::class);