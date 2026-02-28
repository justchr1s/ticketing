<?php

namespace App\Filament\Client\Widgets;

use App\Enums\EtatTicket;
use App\Models\Client;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth('client')->user();

        if (! $user instanceof Client) {
            return [];
        }

        $query = Ticket::query()->where('client_id', $user->id);

        return [
            Stat::make('Mes tickets', (clone $query)->count())
                ->icon('heroicon-o-ticket'),
            Stat::make('Ouverts', (clone $query)->where('etat', EtatTicket::Ouvert)->count())
                ->color('info'),
            Stat::make('En cours', (clone $query)->where('etat', EtatTicket::EnCours)->count())
                ->color('warning'),
            Stat::make('Fermés', (clone $query)->where('etat', EtatTicket::Ferme)->count())
                ->color('success'),
        ];
    }
}
