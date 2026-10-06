<?php

namespace App\Filament\Resources\Ordenanzas\Pages;

use App\Filament\Resources\Ordenanzas\OrdenanzaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrdenanzas extends ListRecords
{
    protected static string $resource = OrdenanzaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
