<?php

use App\Enums\RoleTechnicien;
use App\Models\Technicien;
use App\Models\Ticket;

it('can be created with factory', function () {
    $technicien = Technicien::factory()->create();

    expect($technicien)->toBeInstanceOf(Technicien::class)
        ->and($technicien->nom)->not->toBeEmpty()
        ->and($technicien->email)->not->toBeEmpty();
});

it('can be created as administrator', function () {
    $admin = Technicien::factory()->administrateur()->create();

    expect($admin->role)->toBe(RoleTechnicien::Administrateur)
        ->and($admin->isAdministrateur())->toBeTrue();
});

it('is technicien by default', function () {
    $technicien = Technicien::factory()->create();

    expect($technicien->role)->toBe(RoleTechnicien::Technicien)
        ->and($technicien->isAdministrateur())->toBeFalse();
});

it('has many tickets', function () {
    $technicien = Technicien::factory()->create();
    $client = \App\Models\Client::factory()->create();

    Ticket::factory()->create([
        'technicien_id' => $technicien->id,
        'client_id' => $client->id,
    ]);

    expect($technicien->tickets)->toHaveCount(1);
});
