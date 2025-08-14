<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'generic_name',
        'presentation',
    ];

    public function patientMedicines()
    {
        return $this->hasMany(PatientMedicine::class);
    }
}