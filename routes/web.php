<?php

use App\Http\Controllers\Api\AppointmentController as ApiAppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MedicalRecordController;
use App\Models\Patient;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patient routes
    // Replace the resource route with individual routes
    // Patient routes
    Route::get('patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('patients/{id}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::patch('patients/{patient}/toggle-status', [PatientController::class, 'toggleStatus'])->name('patients.toggle-status');
    // Patient routes
    Route::delete('patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::post('patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore');


  //  Route::get('/gerar-pdf/{id}', [PatientController::class, 'gerarPdf'])->name('patient.pdf');
    Route::get('/gerar-pdf/{id}', [PatientController::class, 'gerarPdf'])->name('gerar.pdf');

    // Appointment routes
    Route::resource('appointments', AppointmentController::class);
    // Remove this line as it's already included in the resource route
     Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    
    // Medical Records routes
    Route::get('patients/{patient}/medical-record', [MedicalRecordController::class, 'show'])
        ->name('patients.medical-record');
    Route::get('patients/{patient}/medical-record/create', [MedicalRecordController::class, 'create'])
        ->name('patients.medical-record.create');
    Route::post('patients/{patient}/medical-record', [MedicalRecordController::class, 'store'])
        ->name('patients.medical-record.store');
    Route::get('patients/{patient}/medical-record/edit', [MedicalRecordController::class, 'edit'])
        ->name('patients.medical-record.edit');
    Route::put('patients/{patient}/medical-record', [MedicalRecordController::class, 'update'])
        ->name('patients.medical-record.update');

    // Evolution routes
    Route::post('medical-records/{medical_record}/evolutions', [MedicalRecordController::class, 'addEvolution'])
        ->name('medical-records.evolutions.store');
    Route::delete('medical-records/evolutions/{evolution}', [MedicalRecordController::class, 'removeEvolution'])
        ->name('medical-records.evolutions.destroy');

    // Remove the resource route as we're using individual routes
    // Route::resource('medical-records', MedicalRecordController::class)...

    Route::get('patients/{patient}/medical-records/create', [MedicalRecordController::class, 'create'])
        ->name('patients.medical-records.create');
    Route::resource('medical-records', MedicalRecordController::class)
        ->except(['create'])
        ->parameters([
            'medical-records' => 'medical_record'
        ]);
    
    // Payment routes
    Route::resource('payments', PaymentController::class);
    Route::get('appointments/{appointment}/payments/create', [PaymentController::class, 'create'])->name('appointments.payments.create');
    
    Route::get('appointments/{appointment}/history', [AppointmentController::class, 'history'])
        ->name('appointments.history');
});

require __DIR__.'/auth.php';
