<?php

use App\Http\Controllers\Api\AppointmentController as ApiAppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MedicalRecordController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patient routes
    Route::resource('patients', PatientController::class);
    Route::put('patients/{patient}/restore', [PatientController::class, 'restore'])->name('patients.restore');
    
    // Appointment routes
    Route::resource('appointments', AppointmentController::class);
    
    Route::middleware(['auth', 'verified'])->group(function () {
        // Medical Records routes
        Route::get('patients/{patient}/medical-records', [MedicalRecordController::class, 'patientRecords'])
            ->name('patients.medical-records');
        Route::get('patients/{patient}/medical-records/create', [MedicalRecordController::class, 'create'])
            ->name('patients.medical-records.create');
        Route::resource('medical-records', MedicalRecordController::class)
            ->except(['create']);
    });
    
    // Payment routes
    Route::resource('payments', PaymentController::class);
    Route::get('appointments/{appointment}/payments/create', [PaymentController::class, 'create'])->name('appointments.payments.create');
    
    Route::get('appointments/{appointment}/history', [AppointmentController::class, 'history'])
        ->name('appointments.history');
});

require __DIR__.'/auth.php';
