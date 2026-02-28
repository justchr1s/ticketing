<?php

namespace App\Filament\Technicien\Resources;

use App\Enums\RoleTechnicien;
use App\Filament\Technicien\Resources\TechnicienResource\Pages;
use App\Models\Technicien;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TechnicienResource extends Resource
{
    protected static ?string $model = Technicien::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Technicien';

    protected static ?string $pluralModelLabel = 'Techniciens';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('telephone')
                    ->label('Téléphone')
                    ->tel()
                    ->maxLength(20),
                TextInput::make('specialite')
                    ->label('Spécialité')
                    ->maxLength(255),
                TextInput::make('mot_de_passe')
                    ->label('Mot de passe')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->maxLength(255),
                Select::make('role')
                    ->label('Rôle')
                    ->options(RoleTechnicien::class)
                    ->required()
                    ->default(RoleTechnicien::Technicien),
            ]);
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
                TextColumn::make('specialite')
                    ->label('Spécialité'),
                TextColumn::make('role')
                    ->label('Rôle')
                    ->badge(),
                TextColumn::make('tickets_count')
                    ->label('Tickets')
                    ->counts('tickets')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTechniciens::route('/'),
            'create' => Pages\CreateTechnicien::route('/create'),
            'edit' => Pages\EditTechnicien::route('/{record}/edit'),
        ];
    }
}
