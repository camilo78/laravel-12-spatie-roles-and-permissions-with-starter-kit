<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Traits\HasRoles;

/**
 * Modelo de Usuario
 * 
 * Gestiona la información de usuarios del sistema incluyendo
 * datos personales, ubicación geográfica y relaciones.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'name',
        'email', 
        'dni',
        'phone',
        'address',
        'department_id',
        'municipality_id',
        'locality_id',
        'gender',
        'status',
        'password',
    ];

    /**
     * Atributos ocultos en la serialización
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Configuración de casting de atributos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /**
     * Campo usado para autenticación
     */
    public function username(): string
    {
        return 'dni';
    }

    /**
     * Genera URL de avatar de Gravatar
     */
    public function gravatarUrl(int $size = 64): string
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp";
    }

    /**
     * Obtiene las iniciales del nombre del usuario (máximo 2)
     */
    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->take(2)
            ->map(fn($part) => strtoupper($part[0] ?? ''))
            ->join('');
    }

    // Relaciones médicas

    /**
     * Patologías del paciente
     */
    public function patientPathologies()
    {
        return $this->hasMany(PatientPathology::class);
    }

     public function deliveryPatients()
    {
        return $this->hasMany(PatientPathology::class);
    }

    // Relaciones geográficas
    
    /**
     * Relación con departamento
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relación con municipio
     */
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    /**
     * Relación con localidad
     */
    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    // Atributos calculados

    /**
     * Nombre del departamento
     */
    public function departmentName(): Attribute
    {
        return Attribute::get(fn() => $this->department?->name);
    }

    /**
     * Nombre del municipio
     */
    public function municipalityName(): Attribute
    {
        return Attribute::get(fn() => $this->municipality?->name);
    }

    /**
     * Nombre de la localidad
     */
    public function localityName(): Attribute
    {
        return Attribute::get(fn() => $this->locality?->name);
    }


}
