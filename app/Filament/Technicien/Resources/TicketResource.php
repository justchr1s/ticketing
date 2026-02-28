<?php

namespace App\Filament\Technicien\Resources;

use App\Enums\EtatTicket;
use App\Enums\PrioriteTicket;
use App\Filament\Technicien\Resources\TicketResource\Pages;
use App\Models\Technicien;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
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
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label('Du'),
                        DatePicker::make('created_until')
                            ->label('Au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('Du '.Carbon::parse($data['created_from'])->format('d/m/Y'))
                                ->removeField('created_from');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('Au '.Carbon::parse($data['created_until'])->format('d/m/Y'))
                                ->removeField('created_until');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Action::make('debuter')
                    ->label('Débuter')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->visible(fn (Ticket $record): bool => $record->etat === EtatTicket::Ouvert && $record->technicien_id !== null)
                    ->authorize('update')
                    ->action(fn (Ticket $record) => $record->transitionTo(EtatTicket::EnCours)),
                Action::make('fermer')
                    ->label('Fermer')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Ticket $record): bool => $record->etat === EtatTicket::EnCours)
                    ->authorize('update')
                    ->requiresConfirmation()
                    ->action(fn (Ticket $record) => $record->transitionTo(EtatTicket::Ferme)),
                Action::make('cloturer')
                    ->label('Clôturer')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->visible(fn (Ticket $record): bool => $record->etat === EtatTicket::EnCours)
                    ->authorize('update')
                    ->requiresConfirmation()
                    ->action(fn (Ticket $record) => $record->transitionTo(EtatTicket::Cloture)),
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
