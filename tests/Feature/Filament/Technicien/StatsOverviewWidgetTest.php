<?php

use App\Enums\EtatTicket;
use App\Filament\Technicien\Widgets\StatsOverviewWidget;
use App\Models\Client;
use App\Models\Technicien;
use App\Models\Ticket;
use Livewire\Livewire;

it('shows unassigned tickets stat for admin', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $client = Client::factory()->create();

    Ticket::factory(2)->create([
        'client_id' => $client->id,
        'technicien_id' => null,
        'etat' => EtatTicket::Ouvert,
    ]);

    Ticket::factory()->create([
        'client_id' => $client->id,
        'technicien_id' => $admin->id,
        'etat' => EtatTicket::Ouvert,
    ]);

    $this->actingAs($admin, 'technicien');

    Livewire::test(StatsOverviewWidget::class)
        ->assertSee('Non assignés')
        ->assertSee('2');
});

it('does not show unassigned tickets stat for non-admin technician', function () {
    $technicien = Technicien::factory()->create();

    $this->actingAs($technicien, 'technicien');

    Livewire::test(StatsOverviewWidget::class)
        ->assertDontSee('Non assignés');
});
