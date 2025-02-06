<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'address',
        'gender',
        'psychologist_id'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}