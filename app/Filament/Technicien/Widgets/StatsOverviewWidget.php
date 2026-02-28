<?php

namespace App\Filament\Technicien\Widgets;

use App\Enums\EtatTicket;
use App\Models\Client;
use App\Models\Technicien;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth('technicien')->user();
        $isAdmin = $user instanceof Technicien && $user->isAdministrateur();

        $ticketQuery = Ticket::query();

        if (! $isAdmin) {
            $ticketQuery->where('technicien_id', $user->id);
        }

        $stats = [
            Stat::make('Total tickets', (clone $ticketQuery)->count())
                ->icon('heroicon-o-ticket'),
            Stat::make('Tickets ouverts', (clone $ticketQuery)->where('etat', EtatTicket::Ouvert)->count())
                ->color('info'),
            Stat::make('Tickets en cours', (clone $ticketQuery)->where('etat', EtatTicket::EnCours)->count())
                ->color('warning'),
            Stat::make('Tickets fermés', (clone $ticketQuery)->where('etat', EtatTicket::Ferme)->count())
                ->color('success'),
            Stat::make('Tickets clôturés', (clone $ticketQuery)->where('etat', EtatTicket::Cloture)->count())
                ->color('gray'),
        ];

        if ($isAdmin) {
            $stats[] = Stat::make('Clients', Client::count())
                ->icon('heroicon-o-user-group');
            $stats[] = Stat::make('Techniciens', Technicien::count())
                ->icon('heroicon-o-users');
        }

        return $stats;
    }
}
