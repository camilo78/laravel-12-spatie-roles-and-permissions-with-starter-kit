<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
    ];

    // Opcional: si tu tabla no usa timestamps
    // public $timestamps = false;

    // Opcional: orden por código
    public function scopeOrdered($query)
    {
        return $query->orderBy('code');
    }
    
    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
