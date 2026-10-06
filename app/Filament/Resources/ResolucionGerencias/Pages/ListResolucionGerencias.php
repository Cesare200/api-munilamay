<?php

namespace App\Filament\Resources\ResolucionGerencias\Pages;

use App\Filament\Resources\ResolucionGerencias\ResolucionGerenciaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResolucionGerencias extends ListRecords
{
    protected static string $resource = ResolucionGerenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
