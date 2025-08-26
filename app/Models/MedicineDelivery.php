<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicineDelivery extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date'];

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
        $currentMonth = now()->format('Y-m');
        return $query->whereRaw('DATE_FORMAT(start_date, "%Y-%m") = ?', [$currentMonth]);
    }

    public function isEditable(): bool
    {
        $currentMonth = now()->format('Y-m');
        return $this->start_date->format('Y-m') === $currentMonth;
    }

    public function isDeletable(): bool
    {
        return $this->isEditable();
    }

    protected static function booted()
    {
        static::deleting(function ($delivery) {
            $delivery->deliveryPatients()->delete();
        });
    }
}