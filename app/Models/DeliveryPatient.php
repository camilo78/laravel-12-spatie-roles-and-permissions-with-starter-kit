<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryPatient extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_delivery_id', 'user_id', 'state', 'delivery_notes'];

    const STATE_PROGRAMADA = 'programada';
    const STATE_EN_PROCESO = 'en_proceso';
    const STATE_ENTREGADA = 'entregada';
    const STATE_NO_ENTREGADA = 'no_entregada';

    protected $casts = [
        'state' => 'string',
    ];

    public function isProgramada(): bool
    {
        return $this->state === self::STATE_PROGRAMADA;
    }

    public function isEnProceso(): bool
    {
        return $this->state === self::STATE_EN_PROCESO;
    }

    public function isEntregada(): bool
    {
        return $this->state === self::STATE_ENTREGADA;
    }

    public function isNoEntregada(): bool
    {
        return $this->state === self::STATE_NO_ENTREGADA;
    }

    public function requiresNotes(): bool
    {
        return $this->state === self::STATE_NO_ENTREGADA;
    }

    public function canEditMedicines(): bool
    {
        return in_array($this->state, [self::STATE_PROGRAMADA, self::STATE_EN_PROCESO]);
    }

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