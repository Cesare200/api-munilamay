<?php

namespace App\Filament\Resources\InstrumentoGestions\Pages;

use App\Filament\Resources\InstrumentoGestions\InstrumentoGestionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstrumentoGestion extends EditRecord
{
    protected static string $resource = InstrumentoGestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}