<?php

namespace App\Filament\Technicien\Widgets;

use App\Enums\EtatTicket;
use App\Models\Technicien;
use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketsParEtatChart extends ChartWidget
{
    protected ?string $heading = 'Tickets par état';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $user = auth('technicien')->user();
        $query = Ticket::query();

        if ($user instanceof Technicien && ! $user->isAdministrateur()) {
            $query->where('technicien_id', $user->id);
        }

        $data = [];
        $labels = [];
        $colors = [];

        foreach (EtatTicket::cases() as $etat) {
            $labels[] = $etat->getLabel();
            $data[] = (clone $query)->where('etat', $etat)->count();
            $colors[] = match ($etat) {
                EtatTicket::Ouvert => '#3b82f6',
                EtatTicket::EnCours => '#f59e0b',
                EtatTicket::Ferme => '#10b981',
                EtatTicket::Cloture => '#6b7280',
            };
        }

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
