<?php

namespace App\Policies;

use App\Models\Technicien;

class TechnicienPolicy
{
    public function viewAny(Technicien $technicien): bool
    {
        return $technicien->isAdministrateur();
    }

    public function view(Technicien $technicien, Technicien $model): bool
    {
        return $technicien->isAdministrateur();
    }

    public function create(Technicien $technicien): bool
    {
        return $technicien->isAdministrateur();
    }

    public function update(Technicien $technicien, Technicien $model): bool
    {
        return $technicien->isAdministrateur();
    }

    public function delete(Technicien $technicien, Technicien $model): bool
    {
        return $technicien->isAdministrateur() && $technicien->id !== $model->id;
    }
}
