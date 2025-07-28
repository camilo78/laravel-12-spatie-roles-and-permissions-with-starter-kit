<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zone extends Model
{
    protected $fillable = ['name', 'description', 'municipality_id'];

    public function municipality(): BelongsTo
    {
        return $this->belongsTo(Municipality::class);
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
