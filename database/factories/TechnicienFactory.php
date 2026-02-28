<?php

namespace Database\Factories;

use App\Enums\RoleTechnicien;
use App\Models\Technicien;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Technicien>
 */
class TechnicienFactory extends Factory
{
    protected $model = Technicien::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake()->phoneNumber(),
            'specialite' => fake()->randomElement(['Réseau', 'Sécurité', 'Développement', 'Support', 'Systèmes']),
            'mot_de_passe' => static::$password ??= Hash::make('password'),
            'role' => RoleTechnicien::Technicien,
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ];
    }

    public function administrateur(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => RoleTechnicien::Administrateur,
        ]);
    }
}
