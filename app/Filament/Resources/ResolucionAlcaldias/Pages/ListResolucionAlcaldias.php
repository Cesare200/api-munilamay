<?php

namespace App\Filament\Resources\ResolucionAlcaldias\Pages;

use App\Filament\Resources\ResolucionAlcaldias\ResolucionAlcaldiaResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction; // Import unificado v5

class ListResolucionAlcaldias extends ListRecords
{
    protected static string $resource = ResolucionAlcaldiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nueva Resolución'),
        ];
    }
}
