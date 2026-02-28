<?php

use App\Enums\EtatTicket;
use App\Enums\PrioriteTicket;
use App\Models\Client;
use App\Models\Technicien;
use App\Models\Ticket;

it('can be created with factory', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket)->toBeInstanceOf(Ticket::class)
        ->and($ticket->titre)->not->toBeEmpty()
        ->and($ticket->description)->not->toBeEmpty();
});

it('belongs to a client', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->client)->toBeInstanceOf(Client::class);
});

it('belongs to a technicien', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->technicien)->toBeInstanceOf(Technicien::class);
});

it('can have no technicien assigned', function () {
    $ticket = Ticket::factory()->ouvert()->create();

    expect($ticket->technicien)->toBeNull();
});

it('casts etat to enum', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->etat)->toBeInstanceOf(EtatTicket::class);
});

it('casts priorite to enum', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket->priorite)->toBeInstanceOf(PrioriteTicket::class);
});

it('deletes tickets when client is deleted', function () {
    $client = Client::factory()->create();
    Ticket::factory(3)->create(['client_id' => $client->id]);

    expect(Ticket::where('client_id', $client->id)->count())->toBe(3);

    $client->delete();

    expect(Ticket::where('client_id', $client->id)->count())->toBe(0);
});

it('nullifies technicien when technicien is deleted', function () {
    $technicien = Technicien::factory()->create();
    $ticket = Ticket::factory()->create(['technicien_id' => $technicien->id]);

    $technicien->delete();

    expect($ticket->fresh()->technicien_id)->toBeNull();
});

it('can transition from Ouvert to EnCours', function () {
    $ticket = Ticket::factory()->create(['etat' => EtatTicket::Ouvert]);

    $ticket->transitionTo(EtatTicket::EnCours);

    expect($ticket->fresh()->etat)->toBe(EtatTicket::EnCours)
        ->and($ticket->fresh()->date_resolution)->toBeNull();
});

it('can transition from EnCours to Ferme and sets date_resolution', function () {
    $ticket = Ticket::factory()->enCours()->create();

    $ticket->transitionTo(EtatTicket::Ferme);

    $fresh = $ticket->fresh();
    expect($fresh->etat)->toBe(EtatTicket::Ferme)
        ->and($fresh->date_resolution)->not->toBeNull();
});

it('can transition from EnCours to Cloture and sets date_resolution', function () {
    $ticket = Ticket::factory()->enCours()->create();

    $ticket->transitionTo(EtatTicket::Cloture);

    $fresh = $ticket->fresh();
    expect($fresh->etat)->toBe(EtatTicket::Cloture)
        ->and($fresh->date_resolution)->not->toBeNull();
});

it('throws exception on invalid transition', function () {
    $ticket = Ticket::factory()->create(['etat' => EtatTicket::Ouvert]);

    $ticket->transitionTo(EtatTicket::Ferme);
})->throws(InvalidArgumentException::class);

it('throws exception when transitioning from terminal state', function () {
    $ticket = Ticket::factory()->create(['etat' => EtatTicket::Ferme]);

    $ticket->transitionTo(EtatTicket::Ouvert);
})->throws(InvalidArgumentException::class);
