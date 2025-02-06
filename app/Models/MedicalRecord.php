<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MedicalRecord extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'patient_id',
        'appointment_id',
        'diagnosis',
        'treatment_plan',
        'observations'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    protected $with = ['patient', 'appointment'];

    protected $appends = ['attachments'];

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('attachments')->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->file_name,
                'url' => $media->getUrl()
            ];
        });
    }
}