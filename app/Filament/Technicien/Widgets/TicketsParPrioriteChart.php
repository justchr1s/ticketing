<?php

namespace App\Filament\Technicien\Widgets;

use App\Enums\PrioriteTicket;
use App\Models\Technicien;
use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketsParPrioriteChart extends ChartWidget
{
    protected ?string $heading = 'Tickets par priorité';

    protected function getType(): string
    {
        return 'bar';
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

        foreach (PrioriteTicket::cases() as $priorite) {
            $labels[] = $priorite->getLabel();
            $data[] = (clone $query)->where('priorite', $priorite)->count();
            $colors[] = match ($priorite) {
                PrioriteTicket::Basse => '#6b7280',
                PrioriteTicket::Moyenne => '#3b82f6',
                PrioriteTicket::Haute => '#f59e0b',
                PrioriteTicket::Urgente => '#ef4444',
            };
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
