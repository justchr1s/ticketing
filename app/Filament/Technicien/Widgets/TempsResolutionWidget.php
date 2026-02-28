<?php

namespace App\Filament\Technicien\Widgets;

use App\Models\Technicien;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TempsResolutionWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth('technicien')->user();
        $query = Ticket::query()->whereNotNull('date_resolution');

        if ($user instanceof Technicien && ! $user->isAdministrateur()) {
            $query->where('technicien_id', $user->id);
        }

        $tickets = $query->get();

        if ($tickets->isEmpty()) {
            return [
                Stat::make('Temps moyen de résolution', 'Aucune donnée')
                    ->icon('heroicon-o-clock'),
            ];
        }

        $totalHours = $tickets->sum(function (Ticket $ticket): float {
            return $ticket->date_resolution->diffInHours($ticket->created_at);
        });

        $averageHours = round($totalHours / $tickets->count(), 1);

        $label = $averageHours >= 24
            ? round($averageHours / 24, 1).' jours'
            : $averageHours.' heures';

        return [
            Stat::make('Temps moyen de résolution', $label)
                ->icon('heroicon-o-clock')
                ->description($tickets->count().' tickets résolus'),
        ];
    }
}
