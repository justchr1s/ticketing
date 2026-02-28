<?php

use App\Enums\RoleTechnicien;

it('has the correct cases', function () {
    expect(RoleTechnicien::cases())->toHaveCount(2);
});

it('has correct values', function (RoleTechnicien $role, string $value) {
    expect($role->value)->toBe($value);
})->with([
    [RoleTechnicien::Administrateur, 'administrateur'],
    [RoleTechnicien::Technicien, 'technicien'],
]);

it('has labels', function (RoleTechnicien $role) {
    expect($role->getLabel())->toBeString()->not->toBeEmpty();
})->with(RoleTechnicien::cases());
