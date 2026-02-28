<?php

use App\Models\Technicien;

it('can access technicien login page', function () {
    $this->get('/technicien/login')->assertSuccessful();
});

it('can authenticate as technicien', function () {
    $technicien = Technicien::factory()->create();

    $this->actingAs($technicien, 'technicien')
        ->get('/technicien')
        ->assertSuccessful();
});

it('cannot access technicien panel without login', function () {
    $this->get('/technicien')->assertRedirect('/technicien/login');
});

it('does not have registration on technicien panel', function () {
    $this->get('/technicien/register')->assertNotFound();
});
