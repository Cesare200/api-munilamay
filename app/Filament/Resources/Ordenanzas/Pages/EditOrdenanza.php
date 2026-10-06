<?php

namespace App\Filament\Resources\Ordenanzas\Pages;

use App\Filament\Resources\Ordenanzas\OrdenanzaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrdenanza extends EditRecord
{
    protected static string $resource = OrdenanzaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
