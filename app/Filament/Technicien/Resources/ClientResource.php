<?php

namespace App\Filament\Technicien\Resources;

use App\Filament\Technicien\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Client';

    protected static ?string $pluralModelLabel = 'Clients';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telephone')
                    ->label('Téléphone'),
                TextColumn::make('adresse')
                    ->label('Adresse')
                    ->limit(30),
                TextColumn::make('tickets_count')
                    ->label('Tickets')
                    ->counts('tickets')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
        ];
    }
}
