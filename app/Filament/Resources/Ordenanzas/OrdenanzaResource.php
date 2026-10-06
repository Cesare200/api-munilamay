<?php

namespace App\Filament\Resources\Ordenanzas;

use App\Filament\Resources\Ordenanzas\Pages\CreateOrdenanza;
use App\Filament\Resources\Ordenanzas\Pages\EditOrdenanza;
use App\Filament\Resources\Ordenanzas\Pages\ListOrdenanzas;
use App\Models\Ordenanza;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;

// NUEVOS IMPORTS UNIFICADOS DE ACCIONES PARA FILAMENT V4/V5
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;

class OrdenanzaResource extends Resource
{
    protected static ?string $model = Ordenanza::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Ordenanzas Municipales';
    
    protected static ?string $pluralModelLabel = 'Ordenanzas Municipales';
    
    protected static ?string $modelLabel = 'Ordenanza Municipal';

    protected static ?string $recordTitleAttribute = 'numero';

public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('numero')
                ->label('Número de Ordenanza')
                ->numeric()
                ->required()
                ->placeholder('Ej: 8'),
                
            TextInput::make('anio')
                ->label('Año')
                ->numeric()
                ->required()
                ->placeholder('Ej: 2017'),
                
            DatePicker::make('fecha')
                ->label('Fecha de Publicación / Aprobación')
                ->required(),
                
            FileUpload::make('pdf')
                ->label('Documento PDF Original')
                ->acceptedFileTypes(['application/pdf']) 
                // CORRECCIÓN: En la v4/v5 se utiliza ->rules()
                ->rules(['mimes:pdf']) 
                ->disk('public')
                ->directory('ordenanzas')
                ->visibility('public')
                ->getUploadedFileNameForStorageUsing(
                    fn ($file) => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension()
                )
                ->required(),



            // NUEVO COMPONENTE: Selector de estado
            Select::make('status')
                ->label('Estado de Publicación')
                ->options([
                    'borrador' => 'Borrador',
                    'publicado' => 'Publicado',
                ])
                ->default('borrador') // Inicia como borrador por defecto
                ->required(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('numero')
                ->label('N° Ordenanza')
                ->sortable()
                ->searchable(),
                
            TextColumn::make('anio')
                ->label('Año')
                ->sortable(),
                
            TextColumn::make('fecha')
                ->label('Fecha')
                ->date('d/m/Y')
                ->sortable(),

            // NUEVA COLUMNA: Muestra el estado como una etiqueta de color
            TextColumn::make('status')
                ->label('Estado')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'borrador' => 'warning',   // Color naranja/amarillo
                    'publicado' => 'success',  // Color verde
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => ucfirst($state))
                ->sortable(),
                
            TextColumn::make('pdf')
                ->label('Documento')
                ->formatStateUsing(fn () => 'Ver PDF')
                ->url(fn ($record) => asset('storage/' . implode('/', array_map('rawurlencode', explode('/', $record->pdf)))), true),
        ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(), // SE QUITA EL PREFIJO 'Tables\Actions\'
            ])
            ->bulkActions([
                BulkActionGroup::make([ // SE QUITA EL PREFIJO 'Tables\Actions\'
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrdenanzas::route('/'),
            'create' => CreateOrdenanza::route('/create'),
            'edit' => EditOrdenanza::route('/{record}/edit'),
        ];
    }
}
