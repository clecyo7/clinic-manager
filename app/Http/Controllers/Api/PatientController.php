<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    public function index(): JsonResponse
    {
        $patients = Patient::with('psychologist')->get();
        return response()->json($patients);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F,O',
            'phone' => 'required|string|max:20',
            'psychologist_id' => 'required|exists:users,id'
        ]);

        $patient = Patient::create($validated);
        return response()->json($patient, 201);
    }

    public function show(Patient $patient): JsonResponse
    {
        return response()->json($patient->load('psychologist', 'appointments'));
    }

    public function update(Request $request, Patient $patient): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'birth_date' => 'date',
            'gender' => 'in:M,F,O',
            'phone' => 'string|max:20',
            'psychologist_id' => 'exists:users,id'
        ]);

        $patient->update($validated);
        return response()->json($patient);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();
        return response()->json(null, 204);
    }
}
