<?php

namespace App\Filament\Exports;

use App\Models\Ticket;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TicketExporter extends Exporter
{
    protected static ?string $model = Ticket::class;

    /**
     * @return array<ExportColumn>
     */
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('titre')
                ->label('Titre'),
            ExportColumn::make('description')
                ->label('Description'),
            ExportColumn::make('etat')
                ->label('État')
                ->formatStateUsing(fn ($state): string => $state?->getLabel() ?? ''),
            ExportColumn::make('priorite')
                ->label('Priorité')
                ->formatStateUsing(fn ($state): string => $state?->getLabel() ?? ''),
            ExportColumn::make('client.nom')
                ->label('Client'),
            ExportColumn::make('technicien.nom')
                ->label('Technicien'),
            ExportColumn::make('created_at')
                ->label('Créé le'),
            ExportColumn::make('date_resolution')
                ->label('Date de résolution'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'L\'export de vos tickets est terminé. '.number_format($export->successful_rows).' '.str('ligne')->plural($export->successful_rows).' exportée(s).';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('ligne')->plural($failedRowsCount).' en échec.';
        }

        return $body;
    }
}
