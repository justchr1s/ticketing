<?php

namespace App\Filament\Technicien\Resources;

use App\Enums\EtatTicket;
use App\Enums\PrioriteTicket;
use App\Filament\Technicien\Resources\TicketResource\Pages;
use App\Models\Technicien;
use App\Models\Ticket;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $modelLabel = 'Ticket';

    protected static ?string $pluralModelLabel = 'Tickets';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titre')
                    ->label('Titre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('etat')
                    ->label('État')
                    ->options(EtatTicket::class)
                    ->required()
                    ->default(EtatTicket::Ouvert),
                Select::make('priorite')
                    ->label('Priorité')
                    ->options(PrioriteTicket::class),
                Select::make('technicien_id')
                    ->label('Technicien assigné')
                    ->relationship('technicien', 'nom')
                    ->searchable()
                    ->preload(),
                Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'nom')
                    ->required()
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('date_resolution')
                    ->label('Date de résolution'),
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
                    ->limit(40),
                TextColumn::make('client.nom')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('technicien.nom')
                    ->label('Technicien')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Non assigné'),
                TextColumn::make('etat')
                    ->label('État')
                    ->badge(),
                TextColumn::make('priorite')
                    ->label('Priorité')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('etat')
                    ->label('État')
                    ->options(EtatTicket::class),
                SelectFilter::make('priorite')
                    ->label('Priorité')
                    ->options(PrioriteTicket::class),
                SelectFilter::make('technicien_id')
                    ->label('Technicien')
                    ->relationship('technicien', 'nom'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth('technicien')->user();

        if ($user instanceof Technicien && ! $user->isAdministrateur()) {
            $query->where('technicien_id', $user->id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
