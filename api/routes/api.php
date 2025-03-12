<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['project' => 'Park\'it']);
});

// auth routes
Route::get('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// user routes
Route::get('/parkings', [ParkingController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/users/{user}/past-reservations', [UserController::class, 'pastReservations']);
    Route::get('/users/{user}/current-reservations', [UserController::class, 'currentReservations']);

    Route::get('/logout', [AuthController::class, 'logout']);

    // parking routes
    Route::post('/parkings', [ParkingController::class, 'store']);
    Route::get('/parkings/{parking:name}', [ParkingController::class, 'show']);
    Route::put('/parkings/{parking}', [ParkingController::class, 'update']);
    Route::delete('/parkings/{parking}', [ParkingController::class, 'destroy']);

    // reservation routes
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations/{parking}', [ReservationController::class, 'store']);
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
    Route::put('/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::put('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel']);
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);

    Route::middleware('admin')->group(function () {
        Route::prefix("admin/statistics")->group(function () {
            Route::get("/", [StatisticsController::class, "overview"]);
            Route::get("/users", [StatisticsController::class, "users"]);
            Route::get("/parkings", [StatisticsController::class, "parkings"]);
            Route::get("/reservations", [StatisticsController::class, "reservations"]);
            Route::get("/revenue", [StatisticsController::class, "revenue"]);
        });
    });
});
