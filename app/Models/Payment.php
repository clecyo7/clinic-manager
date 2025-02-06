<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'amount',
        'payment_method',
        'payment_date',
        'status'
    ];

    protected $casts = [
        'payment_date' => 'datetime'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}