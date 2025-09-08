<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pathology extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'code',
        'description',
        'code_0',
        'code_1',
        'code_2',
        'code_3',
        'code_4',
    ];

    public function patientPathologies()
    {
        return $this->hasMany(PatientPathology::class);
    }
}