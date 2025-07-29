<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $fillable = [
        'name',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function localities()
    {
        return $this->hasMany(Locality::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
