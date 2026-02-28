<?php

namespace App\Filament\Technicien\Resources\TicketResource\Pages;

use App\Enums\EtatTicket;
use App\Filament\Technicien\Resources\TicketResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('debuter')
                ->label('Débuter')
                ->icon('heroicon-o-play')
                ->color('warning')
                ->visible(fn (): bool => $this->record->etat === EtatTicket::Ouvert && $this->record->technicien_id !== null)
                ->authorize('update')
                ->action(function (): void {
                    $this->record->transitionTo(EtatTicket::EnCours);
                    $this->refreshFormData(['etat']);
                }),
            Action::make('fermer')
                ->label('Fermer')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->etat === EtatTicket::EnCours)
                ->authorize('update')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->transitionTo(EtatTicket::Ferme);
                    $this->refreshFormData(['etat', 'date_resolution']);
                }),
            Action::make('cloturer')
                ->label('Clôturer')
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                ->visible(fn (): bool => $this->record->etat === EtatTicket::EnCours)
                ->authorize('update')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->transitionTo(EtatTicket::Cloture);
                    $this->refreshFormData(['etat', 'date_resolution']);
                }),
            DeleteAction::make(),
        ];
    }
}
