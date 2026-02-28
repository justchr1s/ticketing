<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telephone' => fake()->phoneNumber(),
            'adresse' => fake()->address(),
            'mot_de_passe' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
        ];
    }
}
