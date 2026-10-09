<?php

namespace App\Filament\Resources\DocInteres\Pages;

use App\Filament\Resources\DocInteres\DocInteresResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocInteres extends ListRecords
{
    protected static string $resource = DocInteresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}