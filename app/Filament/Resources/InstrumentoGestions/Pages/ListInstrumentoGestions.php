<?php

namespace App\Filament\Resources\InstrumentoGestions\Pages;

use App\Filament\Resources\InstrumentoGestions\InstrumentoGestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstrumentoGestions extends ListRecords
{
    protected static string $resource = InstrumentoGestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}