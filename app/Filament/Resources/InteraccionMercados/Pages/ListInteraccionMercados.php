<?php

namespace App\Filament\Resources\InteraccionMercados\Pages;

use App\Filament\Resources\InteraccionMercados\InteraccionMercadoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInteraccionMercados extends ListRecords
{
    protected static string $resource = InteraccionMercadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}