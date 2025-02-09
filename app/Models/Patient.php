<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'address',
        'profession',
        'marital_status',
        'emergency_contact',
        'psychologist_id'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    protected $appends = [
        'is_active'
    ];

    // Remove the duplicate declaration and comment
    
    // Fix by changing to:


    public function getIsActiveAttribute()
    {
        return !$this->trashed();
    }

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