<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function index()
    {
        return Inertia::render('Patients/Index', [
            'patients' => Patient::where('psychologist_id', auth()->id())->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Patients/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'birth_date' => 'required|date',
            'gender' => 'required|in:masculino,feminino,outro',
            'address' => 'nullable|string',
        ]);
    
        $validated['psychologist_id'] = auth()->id();
        $validated['status'] = 'active';
    
        Patient::create($validated);
    
        return redirect()->route('patients.index')
            ->with('message', 'Paciente cadastrado com sucesso!');
    }

    public function show(Patient $patient)
    {
        if ($patient->psychologist_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Patients/Show', [
            'patient' => $patient
        ]);
    }

    public function edit(Patient $patient)
    {
        if ($patient->psychologist_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Patients/Edit', [
            'patient' => $patient
        ]);
    }

    public function update(Request $request, Patient $patient)
    {
        if ($patient->psychologist_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F,O',
            'phone' => 'required|string|max:20',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('message', 'Paciente atualizado com sucesso!');
    }

    public function destroy(Patient $patient)
    {
        $patient->update(['status' => 'inactive']);
        return redirect()->route('patients.index')
            ->with('message', 'Paciente inativado com sucesso.');
    }

    public function restore(Patient $patient)
    {
        $patient->update(['status' => 'active']);
        return redirect()->route('patients.index')
            ->with('message', 'Paciente reativado com sucesso.');
    }
}
