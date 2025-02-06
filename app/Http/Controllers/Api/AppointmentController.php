<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    public function index(): JsonResponse
    {
        $appointments = Appointment::with(['patient', 'psychologist'])
            ->where('psychologist_id', auth()->id()) // Add filter by authenticated user
            ->orderBy('date_time', 'asc') // Add ordering
            ->get();
            
        return response()->json($appointments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_time' => 'required|date|after:now', // Add validation for future dates
            'notes' => 'nullable|string|max:1000' // Add max length
        ]);

        $validated['psychologist_id'] = auth()->id(); // Set current user as psychologist
        $validated['status'] = 'pendente'; // Set default status

        $appointment = Appointment::create($validated);
        return response()->json($appointment->load('patient', 'psychologist'), 201);
    }

    public function show(Appointment $appointment): JsonResponse
    {
        return response()->json($appointment->load('patient', 'psychologist'));
    }

    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        // Add authorization check
        if ($appointment->psychologist_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'patient_id' => 'exists:patients,id',
            'date_time' => 'date|after:now',
            'status' => 'in:pendente,concluida,cancelada',
            'notes' => 'nullable|string|max:1000'
        ]);

        $appointment->update($validated);
        return response()->json($appointment->load('patient', 'psychologist'));
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        // Add authorization check
        if ($appointment->psychologist_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment->delete();
        return response()->json(null, 204);
    }
}
