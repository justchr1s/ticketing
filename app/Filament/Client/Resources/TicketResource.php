<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\TicketResource\Pages;
use App\Models\Client;
use App\Models\Ticket;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $modelLabel = 'Ticket';

    protected static ?string $pluralModelLabel = 'Mes Tickets';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titre')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('etat')
                    ->label('État')
                    ->badge(),
                TextColumn::make('priorite')
                    ->label('Priorité')
                    ->badge()
                    ->placeholder('Non définie'),
                TextColumn::make('technicien.nom')
                    ->label('Technicien assigné')
                    ->placeholder('Non assigné'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth('client')->user();

        if ($user instanceof Client) {
            $query->where('client_id', $user->id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
        ];
    }
}
