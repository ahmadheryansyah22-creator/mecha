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
use App\Http\Controllers\AiGroqController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ============= PUBLIC ROUTES =============
Route::post('/auth/google', [App\Http\Controllers\GoogleAuthController::class, 'handleGoogle']);
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

// ============= PROTECTED ROUTES =============
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/profile", [AuthController::class, "profile"]);
    Route::post("/profile/update", [AuthController::class, "updateProfile"]);
    Route::post("/change-password", [AuthController::class, "changePassword"]);

    Route::get("/user", function (Request $request) {
        return $request->user();
    });

    Route::apiResource("bengkels", BengkelController::class);
    Route::apiResource("mechanics", MechanicController::class);
    Route::apiResource("vehicles", VehicleController::class);
    Route::apiResource("services", ServiceController::class);
    Route::apiResource("spare-parts", SparePartController::class);
    Route::apiResource("diagnostics", DiagnosticController::class);
    Route::apiResource("orders", OrderController::class);
    Route::apiResource("order-items", OrderItemController::class);
    Route::apiResource("transactions", TransactionController::class);
    Route::apiResource("ratings", RatingController::class);
    Route::apiResource("ai-diagnostics", AiDiagnosticController::class);

    Route::post("/ai-groq", [AiGroqController::class, "diagnose"]);
});



Route::middleware('auth:sanctum')->post('/update-role', function(\Illuminate\Http\Request $request) {
    $user = $request->user();
    $user->role = $request->role;
    $user->save();
    return response()->json(['success' => true, 'message' => 'Role updated']);
});
