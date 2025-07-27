<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'dui',
        'phone',
        'address',
        'department_id',
        'gender',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function gravatarUrl(int $size = 64): string
        {
            $hash = md5(strtolower(trim($this->email)));
            return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp"; // 'd=mp' para imagen de "persona misteriosa" por defecto
        }

    /**
     * Get the initials of the user's name.
     *
     * @return string
     */
    public function initials(): string
    {
        $nameParts = explode(' ', $this->name);
        $initials = '';
        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        return Str::limit($initials, 2, ''); // Limita a las dos primeras iniciales, por ejemplo "JD" para John Doe
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function departmentName(): Attribute
    {
        return Attribute::get(fn () => $this->department?->name);
    }

}
