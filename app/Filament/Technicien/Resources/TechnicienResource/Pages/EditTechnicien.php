<?php

namespace App\Filament\Technicien\Resources\TechnicienResource\Pages;

use App\Filament\Technicien\Resources\TechnicienResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTechnicien extends EditRecord
{
    protected static string $resource = TechnicienResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
