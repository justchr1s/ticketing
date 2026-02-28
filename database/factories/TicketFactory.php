<?php

namespace Database\Factories;

use App\Enums\EtatTicket;
use App\Enums\PrioriteTicket;
use App\Models\Client;
use App\Models\Technicien;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $etat = fake()->randomElement(EtatTicket::cases());

        return [
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'etat' => $etat,
            'priorite' => fake()->randomElement(PrioriteTicket::cases()),
            'technicien_id' => Technicien::factory(),
            'client_id' => Client::factory(),
            'date_resolution' => in_array($etat, [EtatTicket::Ferme, EtatTicket::Cloture])
                ? fake()->dateTimeBetween('-1 month', 'now')
                : null,
        ];
    }

    public function ouvert(): static
    {
        return $this->state(fn (array $attributes) => [
            'etat' => EtatTicket::Ouvert,
            'technicien_id' => null,
            'priorite' => null,
            'date_resolution' => null,
        ]);
    }
}
