<?php

namespace App\Filament\Resources\AcuerdoConcejos\Pages;

use App\Filament\Resources\AcuerdoConcejos\AcuerdoConcejoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAcuerdoConcejos extends ListRecords
{
    protected static string $resource = AcuerdoConcejoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
