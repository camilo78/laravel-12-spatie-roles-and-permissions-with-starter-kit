<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'presentation',
        'concentration',
    ];

    public function patientMedicines()
    {
        return $this->hasMany(PatientMedicine::class);
    }
}