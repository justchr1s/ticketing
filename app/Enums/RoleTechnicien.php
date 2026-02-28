<?php

namespace App\Enums;

enum RoleTechnicien: string
{
    case Administrateur = 'administrateur';
    case Technicien = 'technicien';

    public function getLabel(): string
    {
        return match ($this) {
            self::Administrateur => 'Administrateur',
            self::Technicien => 'Technicien',
        };
    }
}
