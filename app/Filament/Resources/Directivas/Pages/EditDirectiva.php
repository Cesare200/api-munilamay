<?php

namespace App\Filament\Resources\Directivas\Pages;

use App\Filament\Resources\Directivas\DirectivaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDirectiva extends EditRecord
{
    protected static string $resource = DirectivaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
