<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPathology extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pathology_id',
        'diagnosed_at',
        'status',
    ];

    protected $casts = [
        'diagnosed_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pathology()
    {
        return $this->belongsTo(Pathology::class);
    }

    public function patientMedicines()
    {
        return $this->hasMany(PatientMedicine::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithPathology($query, $pathologyId)
    {
        return $query->where('pathology_id', $pathologyId);
    }
}