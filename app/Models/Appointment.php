<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'psychologist_id',
        'date_time',
        'notes',
        'status'
    ];

    protected $casts = [
        'date_time' => 'datetime'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }
}