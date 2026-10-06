<?php

namespace App\Filament\Resources\ConvocatoriaCAS\Pages;

use App\Filament\Resources\ConvocatoriaCAS\ConvocatoriaCASResource;
use Filament\Resources\Pages\CreateRecord;

class CreateConvocatoriaCAS extends CreateRecord
{
    protected static string $resource = ConvocatoriaCASResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Tomamos el número secuencial y rellenamos con ceros
        $numeroConCeros = str_pad($data['numero_secuencial'], 3, '0', STR_PAD_LEFT);
        $anio = $data['anio'];

        // 2. Seteamos los formatos reales que van a la Base de Datos
        $data['codigo_cas'] = "{$numeroConCeros}-{$anio}";
        $data['proceso'] = "CAS N°{$numeroConCeros}-{$anio}-MDL";

        // 3. ¡EL PASO CLAVE! Eliminamos la clave ficticia para que MySQL no se confunda
        unset($data['numero_secuencial']);

        return $data;
    }
}