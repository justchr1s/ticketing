<?php

namespace App\Filament\Technicien\Resources\TicketResource\Pages;

use App\Filament\Exports\TicketExporter;
use App\Filament\Technicien\Resources\TicketResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(TicketExporter::class)
                ->label('Exporter Excel/CSV'),
            Action::make('exportPdf')
                ->label('Exporter PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(route('tickets.export-pdf'))
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }
}
