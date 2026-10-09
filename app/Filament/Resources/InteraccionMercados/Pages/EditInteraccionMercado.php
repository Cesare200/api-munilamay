<?php

namespace App\Filament\Resources\InteraccionMercados\Pages;

use App\Filament\Resources\InteraccionMercados\InteraccionMercadoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInteraccionMercado extends EditRecord
{
    protected static string $resource = InteraccionMercadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}