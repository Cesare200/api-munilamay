<?php

namespace App\Filament\Resources\OrdenamientoTerritorials\Pages;

use App\Filament\Resources\OrdenamientoTerritorials\OrdenamientoTerritorialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrdenamientoTerritorials extends ListRecords
{
    protected static string $resource = OrdenamientoTerritorialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}