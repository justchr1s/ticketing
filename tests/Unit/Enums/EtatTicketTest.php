<?php

use App\Enums\EtatTicket;

it('has the correct cases', function () {
    expect(EtatTicket::cases())->toHaveCount(4);
});

it('has correct values', function (EtatTicket $etat, string $value) {
    expect($etat->value)->toBe($value);
})->with([
    [EtatTicket::Ouvert, 'ouvert'],
    [EtatTicket::EnCours, 'en_cours'],
    [EtatTicket::Ferme, 'ferme'],
    [EtatTicket::Cloture, 'cloture'],
]);

it('has labels', function (EtatTicket $etat) {
    expect($etat->getLabel())->toBeString()->not->toBeEmpty();
})->with(EtatTicket::cases());

it('has colors', function (EtatTicket $etat) {
    expect($etat->getColor())->toBeString()->not->toBeEmpty();
})->with(EtatTicket::cases());
