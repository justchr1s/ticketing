<?php

namespace App\Filament\Technicien\Widgets;

use App\Enums\EtatTicket;
use App\Models\Technicien;
use Filament\Widgets\ChartWidget;

class TicketsParTechnicienChart extends ChartWidget
{
    protected ?string $heading = 'Tickets résolus par technicien';

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        $user = auth('technicien')->user();

        return $user instanceof Technicien && $user->isAdministrateur();
    }

    protected function getData(): array
    {
        $techniciens = Technicien::withCount(['tickets' => function ($query): void {
            $query->whereIn('etat', [EtatTicket::Ferme, EtatTicket::Cloture]);
        }])->get();

        return [
            'datasets' => [
                [
                    'label' => 'Tickets résolus',
                    'data' => $techniciens->pluck('tickets_count')->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $techniciens->pluck('nom')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
        ];
    }
}
