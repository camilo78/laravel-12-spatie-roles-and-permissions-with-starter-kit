<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pathology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
    ];

    public function patientPathologies()
    {
        return $this->hasMany(PatientPathology::class);
    }
}