<?php

use App\Enums\EtatTicket;
use App\Filament\Technicien\Resources\TicketResource\Pages\ListTickets;
use App\Models\Client;
use App\Models\Technicien;
use App\Models\Ticket;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;

it('can list tickets for admin', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $client = Client::factory()->create();
    Ticket::factory(3)->create(['client_id' => $client->id]);

    $this->actingAs($admin, 'technicien')
        ->get('/technicien/tickets')
        ->assertSuccessful();
});

it('can create a ticket', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $client = Client::factory()->create();

    $this->actingAs($admin, 'technicien')
        ->get('/technicien/tickets/create')
        ->assertSuccessful();
});

it('shows debuter action on ouvert ticket with technicien assigned', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $ticket = Ticket::factory()->create([
        'etat' => EtatTicket::Ouvert,
        'technicien_id' => $admin->id,
    ]);

    $this->actingAs($admin, 'technicien');

    Livewire::test(ListTickets::class)
        ->assertActionVisible(TestAction::make('debuter')->table($ticket));
});

it('hides debuter action on ouvert ticket without technicien', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $ticket = Ticket::factory()->ouvert()->create();

    $this->actingAs($admin, 'technicien');

    Livewire::test(ListTickets::class)
        ->assertActionHidden(TestAction::make('debuter')->table($ticket));
});

it('can execute debuter action', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $ticket = Ticket::factory()->create([
        'etat' => EtatTicket::Ouvert,
        'technicien_id' => $admin->id,
    ]);

    $this->actingAs($admin, 'technicien');

    Livewire::test(ListTickets::class)
        ->callAction(TestAction::make('debuter')->table($ticket));

    expect($ticket->fresh()->etat)->toBe(EtatTicket::EnCours);
});

it('shows fermer and cloturer actions on en_cours ticket', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $ticket = Ticket::factory()->enCours()->create([
        'technicien_id' => $admin->id,
    ]);

    $this->actingAs($admin, 'technicien');

    Livewire::test(ListTickets::class)
        ->assertActionVisible(TestAction::make('fermer')->table($ticket))
        ->assertActionVisible(TestAction::make('cloturer')->table($ticket));
});

it('hides fermer and cloturer actions on ouvert ticket', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $ticket = Ticket::factory()->create([
        'etat' => EtatTicket::Ouvert,
        'technicien_id' => $admin->id,
    ]);

    $this->actingAs($admin, 'technicien');

    Livewire::test(ListTickets::class)
        ->assertActionHidden(TestAction::make('fermer')->table($ticket))
        ->assertActionHidden(TestAction::make('cloturer')->table($ticket));
});

it('can execute fermer action', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $ticket = Ticket::factory()->enCours()->create([
        'technicien_id' => $admin->id,
    ]);

    $this->actingAs($admin, 'technicien');

    Livewire::test(ListTickets::class)
        ->callAction(TestAction::make('fermer')->table($ticket));

    $fresh = $ticket->fresh();
    expect($fresh->etat)->toBe(EtatTicket::Ferme)
        ->and($fresh->date_resolution)->not->toBeNull();
});

it('can execute cloturer action', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $ticket = Ticket::factory()->enCours()->create([
        'technicien_id' => $admin->id,
    ]);

    $this->actingAs($admin, 'technicien');

    Livewire::test(ListTickets::class)
        ->callAction(TestAction::make('cloturer')->table($ticket));

    $fresh = $ticket->fresh();
    expect($fresh->etat)->toBe(EtatTicket::Cloture)
        ->and($fresh->date_resolution)->not->toBeNull();
});

it('hides workflow actions on ferme ticket', function () {
    $admin = Technicien::factory()->administrateur()->create();
    $ticket = Ticket::factory()->create([
        'etat' => EtatTicket::Ferme,
        'technicien_id' => $admin->id,
        'date_resolution' => now(),
    ]);

    $this->actingAs($admin, 'technicien');

    Livewire::test(ListTickets::class)
        ->assertActionHidden(TestAction::make('debuter')->table($ticket))
        ->assertActionHidden(TestAction::make('fermer')->table($ticket))
        ->assertActionHidden(TestAction::make('cloturer')->table($ticket));
});
