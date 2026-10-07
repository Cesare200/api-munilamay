<?php

namespace App\Filament\Resources\Decretos\Pages;

use App\Filament\Resources\Decretos\DecretoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDecreto extends EditRecord
{
    protected static string $resource = DecretoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}