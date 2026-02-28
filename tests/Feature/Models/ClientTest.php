<?php

use App\Models\Client;
use App\Models\Ticket;

it('can be created with factory', function () {
    $client = Client::factory()->create();

    expect($client)->toBeInstanceOf(Client::class)
        ->and($client->nom)->not->toBeEmpty()
        ->and($client->email)->not->toBeEmpty();
});

it('has many tickets', function () {
    $client = Client::factory()->create();

    Ticket::factory()->create([
        'client_id' => $client->id,
    ]);

    expect($client->tickets)->toHaveCount(1);
});
