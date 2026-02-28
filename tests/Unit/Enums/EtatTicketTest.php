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

it('allows valid transitions', function (EtatTicket $from, EtatTicket $to) {
    expect($from->canTransitionTo($to))->toBeTrue();
})->with([
    'Ouvert → EnCours' => [EtatTicket::Ouvert, EtatTicket::EnCours],
    'EnCours → Ferme' => [EtatTicket::EnCours, EtatTicket::Ferme],
    'EnCours → Cloture' => [EtatTicket::EnCours, EtatTicket::Cloture],
]);

it('rejects invalid transitions', function (EtatTicket $from, EtatTicket $to) {
    expect($from->canTransitionTo($to))->toBeFalse();
})->with([
    'Ouvert → Ferme' => [EtatTicket::Ouvert, EtatTicket::Ferme],
    'Ouvert → Cloture' => [EtatTicket::Ouvert, EtatTicket::Cloture],
    'Ouvert → Ouvert' => [EtatTicket::Ouvert, EtatTicket::Ouvert],
    'EnCours → Ouvert' => [EtatTicket::EnCours, EtatTicket::Ouvert],
    'EnCours → EnCours' => [EtatTicket::EnCours, EtatTicket::EnCours],
    'Ferme → Ouvert' => [EtatTicket::Ferme, EtatTicket::Ouvert],
    'Ferme → EnCours' => [EtatTicket::Ferme, EtatTicket::EnCours],
    'Ferme → Cloture' => [EtatTicket::Ferme, EtatTicket::Cloture],
    'Cloture → Ouvert' => [EtatTicket::Cloture, EtatTicket::Ouvert],
    'Cloture → EnCours' => [EtatTicket::Cloture, EtatTicket::EnCours],
    'Cloture → Ferme' => [EtatTicket::Cloture, EtatTicket::Ferme],
]);

it('returns correct allowed transitions', function () {
    expect(EtatTicket::Ouvert->allowedTransitions())->toBe([EtatTicket::EnCours])
        ->and(EtatTicket::EnCours->allowedTransitions())->toBe([EtatTicket::Ferme, EtatTicket::Cloture])
        ->and(EtatTicket::Ferme->allowedTransitions())->toBe([])
        ->and(EtatTicket::Cloture->allowedTransitions())->toBe([]);
});
