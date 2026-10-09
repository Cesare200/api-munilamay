<?php

namespace App\Filament\Resources\ProcesosSeleccions\Pages;

use App\Filament\Resources\ProcesosSeleccions\ProcesoSeleccionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProcesoSeleccion extends EditRecord
{
    protected static string $resource = ProcesoSeleccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}