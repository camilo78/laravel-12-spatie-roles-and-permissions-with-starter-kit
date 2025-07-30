<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryPatient extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_delivery_id', 'user_id', 'included'];

    protected $casts = [
        'included' => 'boolean',
    ];

    public function medicineDelivery(): BelongsTo
    {
        return $this->belongsTo(MedicineDelivery::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deliveryMedicines(): HasMany
    {
        return $this->hasMany(DeliveryMedicine::class);
    }
}