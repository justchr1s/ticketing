<?php

namespace App\Filament\Client\Resources\TicketResource\Pages;

use App\Filament\Client\Resources\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['client_id'] = auth('client')->id();

        return $data;
    }
}
