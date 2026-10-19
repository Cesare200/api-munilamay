<?php

namespace App\Filament\Resources\OrdenamientoTerritorials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrdenamientoTerritorialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titulo')
                    ->required(),
                Select::make('categoria')
                    ->options([
            'tomo_1_diagnostico' => 'Tomo 1 diagnostico',
            'tomo_2_propuestas' => 'Tomo 2 propuestas',
            'tomo_3_reglamento' => 'Tomo 3 reglamento',
            'mapa_diagnostico' => 'Mapa diagnostico',
            'mapa_propuesta' => 'Mapa propuesta',
        ])
                    ->required(),
                TextInput::make('subcategoria'),
                Textarea::make('descripcion')
                    ->columnSpanFull(),
                TextInput::make('archivo_pdf')
                    ->required(),
                Toggle::make('estado')
                    ->required(),
            ]);
    }
}
