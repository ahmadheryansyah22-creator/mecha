<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BengkelController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AiDiagnosticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ============= PUBLIC ROUTES (tanpa auth) =============
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ============= PROTECTED ROUTES (dengan auth:sanctum) =============
Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    
    // User route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // API Resources
    Route::apiResource('bengkels', BengkelController::class);
    Route::apiResource('mechanics', MechanicController::class);
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('spare-parts', SparePartController::class);
    Route::apiResource('diagnostics', DiagnosticController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('order-items', OrderItemController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('ratings', RatingController::class);
    Route::apiResource('ai-diagnostics', AiDiagnosticController::class);
});