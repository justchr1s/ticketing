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
