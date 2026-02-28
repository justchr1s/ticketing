<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Technicien;
use App\Models\Ticket;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TicketPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return true;
    }

    public function view(Authenticatable $user, Ticket $ticket): bool
    {
        if ($user instanceof Technicien) {
            return $user->isAdministrateur() || $ticket->technicien_id === $user->id;
        }

        if ($user instanceof Client) {
            return $ticket->client_id === $user->id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Client || $user instanceof Technicien;
    }

    public function update(Authenticatable $user, Ticket $ticket): bool
    {
        if ($user instanceof Technicien) {
            return $user->isAdministrateur() || $ticket->technicien_id === $user->id;
        }

        return false;
    }

    public function delete(Authenticatable $user, Ticket $ticket): bool
    {
        if ($user instanceof Technicien) {
            return $user->isAdministrateur();
        }

        return false;
    }
}
