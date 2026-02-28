<?php

use App\Models\Client;
use App\Models\Ticket;

it('can list own tickets', function () {
    $client = Client::factory()->create();
    Ticket::factory(2)->create(['client_id' => $client->id]);

    $this->actingAs($client, 'client')
        ->get('/client/tickets')
        ->assertSuccessful();
});

it('can access ticket creation page', function () {
    $client = Client::factory()->create();

    $this->actingAs($client, 'client')
        ->get('/client/tickets/create')
        ->assertSuccessful();
});
