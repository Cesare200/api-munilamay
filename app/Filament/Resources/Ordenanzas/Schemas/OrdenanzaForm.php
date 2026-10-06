<?php

namespace App\Filament\Resources\Ordenanzas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrdenanzaForm
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
            ]);
    }
}
