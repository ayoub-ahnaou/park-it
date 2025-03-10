<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['project' => 'Park\'it']);
});

// auth routes
Route::get('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// user routes
Route::get('/parkings', [ParkingController::class, 'index']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}/past-reservations', [UserController::class, 'pastReservations']);
Route::get('/users/{user}/current-reservations', [UserController::class, 'currentReservations']);

// parking routes
Route::post('/parkings', [ParkingController::class, 'store']);
Route::get('/parkings/{parking}', [ParkingController::class, 'show'])->middleware('auth:sanctum');
Route::put('/parkings/{parking}', [ParkingController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/parkings/{parking}', [ParkingController::class, 'destroy'])->middleware('auth:sanctum');

// reservation routes
Route::get('/reservations', [ReservationController::class, 'index'])->middleware('auth:sanctum');
Route::post('/reservations/{parking}', [ReservationController::class, 'store'])->middleware('auth:sanctum');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->middleware('auth:sanctum');
Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->middleware('auth:sanctum');
Route::put('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->middleware('auth:sanctum');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->middleware('auth:sanctum');
