<?php

namespace App\Filament\Resources\ResolucionAlcaldias\Pages;

use App\Filament\Resources\ResolucionAlcaldias\ResolucionAlcaldiaResource;
use Filament\Resources\Pages\EditRecord;

class EditResolucionAlcaldia extends EditRecord
{
    protected static string $resource = ResolucionAlcaldiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Acciones de cabecera si se requieren
        ];
    }
}
