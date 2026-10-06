<?php

namespace App\Filament\Resources\ResolucionGerencias\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ResolucionGerenciaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')
                    ->required()
                    ->numeric(),
                TextInput::make('anio')
                    ->required()
                    ->numeric(),
                DatePicker::make('fecha')
                    ->required(),
                TextInput::make('pdf')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('borrador'),
            ]);
    }
}
