<?php

namespace App\Filament\Resources\ConvocatoriaCAS\Pages; // <-- CORREGIDO AQUÍ

use App\Filament\Resources\ConvocatoriaCAS\ConvocatoriaCASResource;
use Filament\Resources\Pages\EditRecord;

class EditConvocatoriaCAS extends EditRecord
{
    protected static string $resource = ConvocatoriaCASResource::class;
}
