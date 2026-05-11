<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin autenticación)
Route::middleware('throttle:api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Rutas protegidas (requieren token)
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Tickets
    Route::get('/tickets',          [TicketController::class, 'index']);
    Route::get('/tickets/{id}',     [TicketController::class, 'show']);
    Route::post('/tickets',         [TicketController::class, 'store']);
    Route::put('/tickets/{id}',     [TicketController::class, 'update']);
    Route::delete('/tickets/{id}',  [TicketController::class, 'destroy']);

    // Devices
    Route::get('/devices',          [DeviceController::class, 'index']);
    Route::post('/devices/assign',  [DeviceController::class, 'assign']);
});
