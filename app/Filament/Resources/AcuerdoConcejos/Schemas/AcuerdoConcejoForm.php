<?php

namespace App\Filament\Resources\AcuerdoConcejos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AcuerdoConcejoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')->label('Número de Acuerdo')->required()->numeric(),
                TextInput::make('anio')->label('Año')->required()->numeric(),
                DatePicker::make('fecha')->label('Fecha de Aprobación')->required(),
                FileUpload::make('pdf')
                    ->label('Documento PDF Original')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules(['mimes:pdf'])
                    ->disk('public')
                    ->directory('acuerdos-concejo')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(
                        fn ($file) => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension()
                    )
                    ->required(),
                Select::make('status')
                    ->label('Estado de Publicación')
                    ->options(['borrador' => 'Borrador', 'publicado' => 'Publicado'])
                    ->default('borrador')
                    ->required(),
            ]);
    }
}
