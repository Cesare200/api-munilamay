<?php

namespace App\Filament\Resources\DocInteres\Pages;

use App\Filament\Resources\DocInteres\DocInteresResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocInteres extends EditRecord
{
    protected static string $resource = DocInteresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}