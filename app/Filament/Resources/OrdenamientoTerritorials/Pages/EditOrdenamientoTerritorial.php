<?php

namespace App\Filament\Resources\OrdenamientoTerritorials\Pages;

use App\Filament\Resources\OrdenamientoTerritorials\OrdenamientoTerritorialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrdenamientoTerritorial extends EditRecord
{
    protected static string $resource = OrdenamientoTerritorialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
