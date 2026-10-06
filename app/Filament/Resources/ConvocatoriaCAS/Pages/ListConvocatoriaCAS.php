<?php

namespace App\Filament\Resources\ConvocatoriaCAS\Pages;

use App\Filament\Resources\ConvocatoriaCAS\ConvocatoriaCASResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction; // <-- IMPORTANTE: Importar la acción de Filament v5

class ListConvocatoriaCAS extends ListRecords
{
    protected static string $resource = ConvocatoriaCASResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // AGREGA ESTA LÍNEA PARA PINTAR EL BOTÓN DE CREACIÓN
            CreateAction::make()
                ->label('Nueva Convocatoria CAS'),
        ];
    }
}
