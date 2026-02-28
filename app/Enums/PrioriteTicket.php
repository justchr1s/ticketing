<?php

namespace App\Enums;

enum PrioriteTicket: string
{
    case Basse = 'basse';
    case Moyenne = 'moyenne';
    case Haute = 'haute';
    case Urgente = 'urgente';

    public function getLabel(): string
    {
        return match ($this) {
            self::Basse => 'Basse',
            self::Moyenne => 'Moyenne',
            self::Haute => 'Haute',
            self::Urgente => 'Urgente',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Basse => 'gray',
            self::Moyenne => 'info',
            self::Haute => 'warning',
            self::Urgente => 'danger',
        };
    }
}
