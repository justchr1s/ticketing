<?php

namespace App\Filament\Technicien\Widgets;

use App\Models\Technicien;
use App\Models\Ticket;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DerniersTicketsWidget extends BaseWidget
{
    protected static ?string $heading = 'Derniers tickets créés';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = auth('technicien')->user();
        $query = Ticket::query()->latest();

        if ($user instanceof Technicien && ! $user->isAdministrateur()) {
            $query->where('technicien_id', $user->id);
        }

        return $table
            ->query($query->limit(5))
            ->columns([
                TextColumn::make('titre')
                    ->label('Titre')
                    ->limit(40),
                TextColumn::make('client.nom')
                    ->label('Client'),
                TextColumn::make('etat')
                    ->label('État')
                    ->badge(),
                TextColumn::make('priorite')
                    ->label('Priorité')
                    ->badge()
                    ->placeholder('Non définie'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->paginated(false);
    }
}
