<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $patients = [
            'total' => Patient::where('psychologist_id', auth()->id())->withTrashed()->count(),
            'active' => Patient::where('psychologist_id', auth()->id())->count(),
            'inactive' => Patient::where('psychologist_id', auth()->id())->onlyTrashed()->count(),
        ];
    
        $appointments = [
            'total' => Appointment::where('psychologist_id', auth()->id())->count(),
            'completed' => Appointment::where('psychologist_id', auth()->id())
                        ->where('status', 'concluida')
                        ->count(),
            'pending' => Appointment::where('psychologist_id', auth()->id())
                        ->where('status', 'pendente')
                        ->count(),
            'canceled' => Appointment::where('psychologist_id', auth()->id())
                        ->where('status', 'cancelada')
                        ->count(),
        ];
    
        $payments = [
            'total' => Payment::whereHas('appointment', function ($query) {
                $query->where('psychologist_id', auth()->id());
            })->sum('amount'),
            'paid' => Payment::whereHas('appointment', function ($query) {
                $query->where('psychologist_id', auth()->id());
            })->where('status', 'paid')->sum('amount'),
            'pending' => Payment::whereHas('appointment', function ($query) {
                $query->where('psychologist_id', auth()->id());
            })->where('status', 'pending')->sum('amount'),
            'canceled' => Payment::whereHas('appointment', function ($query) {
                $query->where('psychologist_id', auth()->id());
            })->where('status', 'cancelled')->sum('amount'),
        ];
    
        return Inertia::render('Dashboard', [
            'statistics' => [
                'patients' => $patients,
                'appointments' => $appointments,
                'payments' => $payments,
                'monthlyAppointments' => $this->getMonthlyAppointments(),
                'monthlyRevenues' => $this->getMonthlyRevenues(),
            ]
        ]);
    }

    private function getMonthlyAppointments()
    {
        return Appointment::where('psychologist_id', auth()->id())
            ->select(
                DB::raw("strftime('%m', date_time) as month"),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('date_time', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }

    private function getMonthlyRevenues()
    {
        return Payment::select(
                DB::raw("strftime('%m', created_at) as month"),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
    }
}




