<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;


class PatientController extends Controller
{
    public function index()
    {
        return Inertia::render('Patients/Index', [
            'patients' => Patient::withTrashed()
                ->where('psychologist_id', auth()->id())
                ->get()
                ->map(function ($patient) {
                    return [
                        'id' => $patient->id ?? null,
                        'name' => $patient->name ?? '',
                        'email' => $patient->email ?? '',
                        'phone' => $patient->phone ?? '',
                        'birth_date' => $patient->birth_date ?? null,
                        'address' => $patient->address ?? '',
                        'gender' => $patient->gender ?? '',
                        'is_active' => !($patient->deleted_at ?? false),
                        'deleted_at' => $patient->deleted_at ?? null
                    ];
                })
                ->values()
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
            'address.street' => 'nullable|string|max:255',
            'address.number' => 'nullable|string|max:20',
            'address.complement' => 'nullable|string|max:255',
            'address.neighborhood' => 'nullable|string|max:255',
            'address.city' => 'nullable|string|max:255',
            'address.state' => 'nullable|string|size:2',
            'address.zip_code' => 'nullable|string|max:9',
            'profession' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
        ]);
    
        $validated['psychologist_id'] = auth()->id();
        $validated['address'] = json_encode($validated['address']);
        
        Patient::create($validated);
    
        return redirect()->route('patients.index')
            ->with('message', 'Paciente cadastrado com sucesso!');
    }

    public function show($id)
    {
        $patient = Patient::withTrashed()
            ->where('id', $id)
            ->where('psychologist_id', auth()->id())
            ->firstOrFail();
    
        $address = json_decode($patient->address, true) ?? [
            'street' => '',
            'number' => '',
            'complement' => '',
            'neighborhood' => '',
            'city' => '',
            'state' => '',
            'zip_code' => ''
        ];
    
        return Inertia::render('Patients/Show', [
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'phone' => $patient->phone,
                'birth_date' => $patient->birth_date,
                'address' => $address,
                'gender' => $patient->gender,
                'profession' => $patient->profession,
                'marital_status' => $patient->marital_status,
                'emergency_contact' => $patient->emergency_contact,
                'is_active' => !$patient->trashed(),
                'deleted_at' => $patient->deleted_at,
                'appointments' => $patient->appointments()->with('payment')->get(),
                'medicalRecords' => $patient->medicalRecords
            ]
        ]);
    }

    public function edit($id)
    {
        $patient = Patient::withTrashed()
            ->where('id', $id)
            ->where('psychologist_id', auth()->id())
            ->firstOrFail();
    
        $address = json_decode($patient->address, true) ?? [
            'street' => '',
            'number' => '',
            'complement' => '',
            'neighborhood' => '',
            'city' => '',
            'state' => '',
            'zip_code' => ''
        ];
    
        return Inertia::render('Patients/Edit', [
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'phone' => $patient->phone,
                'birth_date' => $patient->birth_date,
                'address' => $address,
                'gender' => $patient->gender,
                'profession' => $patient->profession,
                'marital_status' => $patient->marital_status,
                'emergency_contact' => $patient->emergency_contact,
                'is_active' => !$patient->trashed()
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::withTrashed()
            ->where('id', $id)
            ->where('psychologist_id', auth()->id())
            ->firstOrFail();
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:masculino,feminino,outro',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address.street' => 'nullable|string|max:255',
            'address.number' => 'nullable|string|max:20',
            'address.complement' => 'nullable|string|max:255',
            'address.neighborhood' => 'nullable|string|max:255',
            'address.city' => 'nullable|string|max:255',
            'address.state' => 'nullable|string|size:2',
            'address.zip_code' => 'nullable|string|max:9',
            'profession' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
        ]);
    
        // Convert address to JSON before saving
        $validated['address'] = json_encode($validated['address']);
    
        $patient->update($validated);
    
        return redirect()->route('patients.index')
            ->with('message', 'Paciente atualizado com sucesso!');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->psychologist_id !== auth()->id()) {
            abort(403);
        }
    
        try {
            $patient->delete(); // This will use soft delete
            return redirect()->route('patients.index')
                ->with('message', 'Paciente desativado com sucesso.');
        } catch (\Exception $e) {
            \Log::error('Erro ao desativar paciente: ' . $e->getMessage());
            return back()->with('error', 'Erro ao desativar o paciente. Por favor, tente novamente.');
        }
    }

    public function restore($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        
        if ($patient->psychologist_id !== auth()->id()) {
            abort(403);
        }
    
        $patient->restore();
    
        return redirect()->route('patients.index')
            ->with('message', 'Paciente reativado com sucesso!');
    }

    public function gerarPdf($id)
    {
        $clinica = [
            'nome' => 'PsiCloud - Cuidando da sua Saúde Mental',
            'endereco' => 'Av. Central, 123 - Centro, São Paulo - SP',
            'telefone' => '+55 61 98203-9671',
            'email' => 'contato@psicloud.com',
        ];
    
        $patient = Patient::where('id', $id)
                          ->where('psychologist_id', auth()->id())
                          ->firstOrFail();
    
        $endereco = $patient->address ? json_decode($patient->address, true) : [];
        $formattedAddress = isset($endereco['street']) 
            ? "{$endereco['street']}, {$endereco['number']} - {$endereco['neighborhood']}, {$endereco['city']} - {$endereco['state']}"
            : 'Não informado';
    
        $dados = [
            'clinica' => $clinica,
            'titulo' => 'Relatório Individual do Paciente',
            'paciente' => [
                'nome' => $patient->name,
                'email' => $patient->email ?? 'Não informado',
                'telefone' => $patient->phone ?? 'Não informado',
                'data_nascimento' => $patient->birth_date ? date('d/m/Y', strtotime($patient->birth_date)) : 'Não informado',
                'genero' => ucfirst($patient->gender),
                'profissao' => $patient->profession ?? 'Não informado',
                'estado_civil' => ucfirst($patient->marital_status) ?? 'Não informado',
                'contato_emergencia' => $patient->emergency_contact ?? 'Não informado',
                'endereco' => $formattedAddress,
            ],
        ];
    
        $pdf = Pdf::loadView('pdf.relatorio', $dados);
    
        // Exibir PDF no navegador
        return $pdf->stream("relatorio_paciente_{$patient->id}.pdf");
    }
    

    
    
}
