<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicineDelivery extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date', 'status'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function deliveryPatients(): HasMany
    {
        return $this->hasMany(DeliveryPatient::class);
    }

    public function scopeEditable($query)
    {
        return $query->where('start_date', '>', now()->toDateString());
    }

    public function isEditable(): bool
    {
        return $this->start_date > now()->toDateString();
    }
}