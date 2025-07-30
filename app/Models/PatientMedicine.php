<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_pathology_id',
        'medicine_id',
        'dosage',
        'quantity',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function patientPathology()
    {
        return $this->belongsTo(PatientPathology::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}