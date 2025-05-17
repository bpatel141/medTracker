<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrugSearchController;
use App\Http\Controllers\MedicationController;
use App\Http\Middleware\CustomThrottleRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware([CustomThrottleRequests::class . ':10,1'])
    ->get('/drugs/search', [DrugSearchController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/medications', [MedicationController::class, 'store']);
    Route::get('/user/medications', [MedicationController::class, 'index']);
    Route::delete('/user/medications/{rxcui}', [MedicationController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
});