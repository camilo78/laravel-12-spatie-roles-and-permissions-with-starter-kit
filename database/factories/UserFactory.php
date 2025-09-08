<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $municipality = \App\Models\Municipality::where('department_id', 1)->inRandomOrder()->first();
        $locality = \App\Models\Locality::where('municipality_id', $municipality->id)->inRandomOrder()->first();
        
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'dni' => fake()->numerify('#############'),
            'phone' => fake()->phoneNumber(),
            'department_id' => 1, // Atlántida
            'municipality_id' => $municipality->id,
            'locality_id' => $locality ? $locality->id : 1,
            'address' => fake()->address(),
            'email_verified_at' => now(),
            'gender' => fake()->randomElement(['Masculino', 'Femenino']),
            'status' => true,
            'departmental_delivery' => fake()->boolean(20), // 20% probabilidad de ser true
            'admission_date' => fake()->dateTimeBetween('2024-01-01', '2025-08-31')->format('Y-m-d'),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
