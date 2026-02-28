<?php

use App\Enums\PrioriteTicket;

it('has the correct cases', function () {
    expect(PrioriteTicket::cases())->toHaveCount(4);
});

it('has correct values', function (PrioriteTicket $priorite, string $value) {
    expect($priorite->value)->toBe($value);
})->with([
    [PrioriteTicket::Basse, 'basse'],
    [PrioriteTicket::Moyenne, 'moyenne'],
    [PrioriteTicket::Haute, 'haute'],
    [PrioriteTicket::Urgente, 'urgente'],
]);

it('has labels', function (PrioriteTicket $priorite) {
    expect($priorite->getLabel())->toBeString()->not->toBeEmpty();
})->with(PrioriteTicket::cases());

it('has colors', function (PrioriteTicket $priorite) {
    expect($priorite->getColor())->toBeString()->not->toBeEmpty();
})->with(PrioriteTicket::cases());
