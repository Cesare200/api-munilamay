<?php

namespace App\Filament\Resources\ResolucionGerencias\Pages;

use App\Filament\Resources\ResolucionGerencias\ResolucionGerenciaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResolucionGerencia extends EditRecord
{
    protected static string $resource = ResolucionGerenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
