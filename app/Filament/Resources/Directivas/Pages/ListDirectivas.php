<?php

namespace App\Filament\Resources\Directivas\Pages;

use App\Filament\Resources\Directivas\DirectivaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDirectivas extends ListRecords
{
    protected static string $resource = DirectivaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
