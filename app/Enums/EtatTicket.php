<?php

namespace App\Enums;

enum EtatTicket: string
{
    case Ouvert = 'ouvert';
    case EnCours = 'en_cours';
    case Ferme = 'ferme';
    case Cloture = 'cloture';

    public function getLabel(): string
    {
        return match ($this) {
            self::Ouvert => 'Ouvert',
            self::EnCours => 'En cours',
            self::Ferme => 'Fermé',
            self::Cloture => 'Clôturé',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Ouvert => 'info',
            self::EnCours => 'warning',
            self::Ferme => 'success',
            self::Cloture => 'gray',
        };
    }

    /**
     * @return array<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Ouvert => [self::EnCours],
            self::EnCours => [self::Ferme, self::Cloture],
            self::Ferme, self::Cloture => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}
