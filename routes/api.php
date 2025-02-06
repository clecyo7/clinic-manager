<?php

use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('appointments', AppointmentController::class);
});