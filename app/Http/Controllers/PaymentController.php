<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        return Inertia::render('Payments/Index', [
            'payments' => Payment::with(['appointment.patient'])
                ->whereHas('appointment', function($query) {
                    $query->where('psychologist_id', auth()->id());
                })
                ->latest()
                ->get()
        ]);
    }

    public function create(Appointment $appointment)
    {
        return Inertia::render('Payments/Create', [
            'appointment' => $appointment->load('patient')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:dinheiro,cartao_credito,cartao_debito,pix,transferencia',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        $payment = Payment::create($validated);

        return redirect()->route('payments.show', $payment)
            ->with('message', 'Pagamento registrado com sucesso!');
    }

    public function show(Payment $payment)
    {
        return Inertia::render('Payments/Show', [
            'payment' => $payment->load(['appointment.patient'])
        ]);
    }

    public function edit(Payment $payment)
    {
        return Inertia::render('Payments/Edit', [
            'payment' => $payment->load(['appointment.patient'])
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:dinheiro,cartao_credito,cartao_debito,pix,transferencia',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)
            ->with('message', 'Pagamento atualizado com sucesso!');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('message', 'Pagamento excluído com sucesso!');
    }
}