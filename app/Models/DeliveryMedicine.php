<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryMedicine extends Model
{
    use HasFactory;

    protected $fillable = ['delivery_patient_id', 'patient_medicine_id', 'included', 'observations'];

    protected $casts = [
        'included' => 'boolean',
    ];

    public function deliveryPatient(): BelongsTo
    {
        return $this->belongsTo(DeliveryPatient::class);
    }

    public function patientMedicine(): BelongsTo
    {
        return $this->belongsTo(PatientMedicine::class);
    }
}