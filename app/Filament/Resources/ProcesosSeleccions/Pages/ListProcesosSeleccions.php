<?php

namespace App\Filament\Resources\ProcesosSeleccions\Pages;

use App\Filament\Resources\ProcesosSeleccions\ProcesoSeleccionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProcesosSeleccions extends ListRecords
{
    protected static string $resource = ProcesoSeleccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}