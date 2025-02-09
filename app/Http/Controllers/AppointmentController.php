<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index()
    {
        return Inertia::render('Appointments/Index', [
            'appointments' => Appointment::with('patient')
                ->where('psychologist_id', auth()->id())
                ->orderBy('date')
                ->orderBy('start_time')
                ->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Appointments/Create', [
            'patients' => Patient::where('psychologist_id', auth()->id())
                ->orderBy('name')
                ->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_time' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['psychologist_id'] = auth()->id();
        $validated['status'] = 'pendente';

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('message', 'Consulta agendada com sucesso!');
    }

    public function show(Appointment $appointment)
    {
        return Inertia::render('Appointments/Show', [
            'appointment' => $appointment->load('patient')
        ]);
    }

    public function edit(Appointment $appointment)
    {
        return Inertia::render('Appointments/Edit', [
            'appointment' => $appointment->load('patient'),
            'patients' => Patient::where('psychologist_id', auth()->id())
                ->orderBy('name')
                ->get()
        ]);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_time' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:pendente,concluida,cancelada'
        ]);
    
        $oldStatus = $appointment->status;
        $appointment->update($validated);
    
        // Registrar mudança no histórico se o status mudou
        if ($oldStatus !== $validated['status']) {
            AppointmentHistory::create([
                'appointment_id' => $appointment->id,
                'status' => $validated['status'],
                'notes' => $request->notes,
                'changed_by' => auth()->id()
            ]);
        }
    
        return redirect()->route('appointments.index')
            ->with('message', 'Consulta atualizada com sucesso!');
    }

    public function destroy(Appointment $appointment)
    {
        try {
            // Check for related medical records
            if ($appointment->medicalRecords()->exists()) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir esta consulta pois existem prontuários vinculados.');
            }

            // Check for related payments
            if ($appointment->payment()->exists()) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir esta consulta pois existem pagamentos vinculados.');
            }

            $appointment->delete();
            return redirect()->route('appointments.index')
                ->with('message', 'Consulta excluída com sucesso!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir esta consulta. Verifique se existem registros vinculados.');
        }
    }

    public function history(Appointment $appointment)
    {
        return Inertia::render('Appointments/History', [
            'appointment' => $appointment->load(['patient', 'histories.user'])
        ]);
    }
}