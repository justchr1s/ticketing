<?php

use App\Models\Client;

it('can access client login page', function () {
    $this->get('/client/login')->assertSuccessful();
});

it('can access client registration page', function () {
    $this->get('/client/register')->assertSuccessful();
});

it('can authenticate as client', function () {
    $client = Client::factory()->create();

    $this->actingAs($client, 'client')
        ->get('/client')
        ->assertSuccessful();
});

it('cannot access client panel without login', function () {
    $this->get('/client')->assertRedirect('/client/login');
});
