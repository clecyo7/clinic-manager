<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MedicalRecordController extends Controller
{
    public function index()
    {
        return Inertia::render('MedicalRecords/Index', [
            'records' => MedicalRecord::with(['patient', 'appointment'])
                ->whereHas('appointment', function($query) {
                    $query->where('psychologist_id', auth()->id());
                })
                ->latest()
                ->get()
        ]);
    }

    public function create(Patient $patient)
    {
        return Inertia::render('MedicalRecords/Create', [
            'patient' => $patient,
            'appointments' => $patient->appointments()
                ->where('psychologist_id', auth()->id())
                ->orderBy('date_time', 'desc')
                ->get()
        ]);
    }

    public function store(Request $request)
    {
//dd($request->all());  // Fixed: removed the $ from dd
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'observations' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240' // 10MB max per file
        ]);

        $record = MedicalRecord::create($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $record->addMedia($file)
                    ->toMediaCollection('attachments');
            }
        }

        return redirect()->route('medical-records.show', $record)
            ->with('message', 'Prontuário criado com sucesso!');
    }

    public function show(MedicalRecord $record)
    {
        $record->load(['patient', 'appointment', 'media']);
        
        $record->attachments = $record->getMedia('attachments')->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->file_name,
                'url' => $media->getUrl()
            ];
        });
        
        return Inertia::render('MedicalRecords/Show', [
            'record' => $record
        ]);
    }

    public function edit(MedicalRecord $record)
    {
        $record->load(['patient', 'appointment']);
        
        return Inertia::render('MedicalRecords/Edit', [
            'record' => $record
        ]);
    }

    public function update(Request $request, MedicalRecord $record)
    {
        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'observations' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240'
        ]);

        $record->update($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $record->addMedia($file)
                    ->toMediaCollection('attachments');
            }
        }

        return redirect()->route('medical-records.show', $record)
            ->with('message', 'Prontuário atualizado com sucesso!');
    }

    public function destroy(MedicalRecord $record)
    {
        $record->delete();

        return redirect()->route('medical-records.index')
            ->with('message', 'Prontuário excluído com sucesso!');
    }

    public function patientRecords(Patient $patient)
    {
        return Inertia::render('MedicalRecords/Index', [
            'records' => MedicalRecord::with(['patient', 'appointment'])
                ->where('patient_id', $patient->id)
                ->whereHas('appointment', function($query) {
                    $query->where('psychologist_id', auth()->id());
                })
                ->latest()
                ->get(),
            'patient' => $patient
        ]);
    }
}
