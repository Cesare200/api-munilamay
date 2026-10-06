<?php

namespace App\Filament\Resources\AcuerdoConcejos\Pages;

use App\Filament\Resources\AcuerdoConcejos\AcuerdoConcejoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAcuerdoConcejo extends EditRecord
{
    protected static string $resource = AcuerdoConcejoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
