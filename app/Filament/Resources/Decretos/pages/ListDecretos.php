<?php

namespace App\Filament\Resources\Decretos\Pages;

use App\Filament\Resources\Decretos\DecretoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDecretos extends ListRecords
{
    protected static string $resource = DecretoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}