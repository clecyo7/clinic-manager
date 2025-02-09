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
            'records' => MedicalRecord::with(['appointment.patient'])
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
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'observations' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240'
        ], [
            'required' => 'O campo :attribute é obrigatório',
            'exists' => 'O :attribute selecionado é inválido',
            'string' => 'O campo :attribute deve ser um texto',
            'file' => 'O arquivo deve ser válido',
            'max' => 'O arquivo não deve ser maior que 10MB'
        ]);

        try {
            $record = MedicalRecord::create($validated);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $record->addMedia($file)
                        ->preservingOriginal()
                        ->toMediaCollection('attachments', 'public');
                }
            }

            return redirect()
                ->route('patients.medical-records', ['patient' => $validated['patient_id']])
                ->with('message', 'Prontuário criado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao criar prontuário: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar o prontuário. Por favor, tente novamente.');
        }
    }

    public function show(MedicalRecord $medical_record)
    {
        $medical_record->load(['patient', 'appointment']);
        
        try {
            $attachments = $medical_record->getMedia('attachments')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'url' => $media->getUrl(),
                    'size' => $media->size,
                    'mime_type' => $media->mime_type
                ];
            });

            $medical_record->attachments = $attachments;
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar anexos: ' . $e->getMessage());
            $medical_record->attachments = [];
        }
        
        return Inertia::render('MedicalRecords/Show', [
            'record' => $medical_record
        ]);
    }

    // Also update other methods to use consistent parameter naming
    public function edit(MedicalRecord $medical_record)
    {
        $medical_record->load(['patient', 'appointment']);
        
        try {
            $attachments = $medical_record->getMedia('attachments')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'url' => $media->getUrl(),
                    'size' => $media->size,
                    'mime_type' => $media->mime_type
                ];
            });
            
            $medical_record->attachments = $attachments;
        } catch (\Exception $e) {
            \Log::error('Erro ao carregar anexos: ' . $e->getMessage());
            $medical_record->attachments = [];
        }
        
        return Inertia::render('MedicalRecords/Edit', [
            'record' => $medical_record
        ]);
    }

    public function destroy(MedicalRecord $medical_record)
    {
        try {
            // Obtém o ID do paciente antes de excluir o prontuário
            $patient_id = $medical_record->patient_id;
    
            // Deleta o prontuário
            $medical_record->delete();
    
            // Redireciona para a lista de prontuários do paciente com mensagem de sucesso
            return redirect()
                ->route('patients.medical-records', ['patient' => $patient_id])
                ->with('message', 'Prontuário excluído com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir prontuário: ' . $e->getMessage());
    
            // Retorna à página anterior com mensagem de erro
            return back()->with('error', 'Erro ao excluir o prontuário. Por favor, tente novamente.');
        }
    }
    
    

    public function update(Request $request, MedicalRecord $medical_record)
    {
        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'observations' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240'
        ], [
            'required' => 'O campo :attribute é obrigatório',
            'string' => 'O campo :attribute deve ser um texto',
            'file' => 'O arquivo deve ser válido',
            'max' => 'O arquivo não deve ser maior que 10MB'
        ]);

        try {
            $medical_record->update($validated);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $medical_record->addMedia($file)
                        ->preservingOriginal()
                        ->toMediaCollection('attachments', 'public');
                }
            }

            return redirect()
                ->route('patients.medical-records', ['patient' => $medical_record->patient_id])
                ->with('message', 'Prontuário atualizado com sucesso!');

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar prontuário: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar o prontuário. Por favor, tente novamente.');
        }
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
