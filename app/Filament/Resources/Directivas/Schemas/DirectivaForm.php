<?php

namespace App\Filament\Resources\Directivas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DirectivaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titulo')
                    ->required(),
                Textarea::make('descripcion')
                    ->required()
                    ->columnSpanFull(),
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
