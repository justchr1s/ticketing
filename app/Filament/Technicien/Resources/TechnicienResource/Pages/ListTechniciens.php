<?php

namespace App\Filament\Technicien\Resources\TechnicienResource\Pages;

use App\Filament\Technicien\Resources\TechnicienResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTechniciens extends ListRecords
{
    protected static string $resource = TechnicienResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
