<?php

use App\Models\Client;
use App\Models\Technicien;
use App\Models\Ticket;

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
