<?php

namespace App\Filament\Resources\ConvocatoriaCAS;

use App\Filament\Resources\ConvocatoriaCAS\Pages\CreateConvocatoriaCAS;
use App\Filament\Resources\ConvocatoriaCAS\Pages\EditConvocatoriaCAS;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

use Filament\Actions\Action; 
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Models\ConvocatoriaCAS; // <-- Asegúrate de que no tenga la "s" ni el guión bajo

class ConvocatoriaCASResource extends Resource
{
    protected static ?string $model = ConvocatoriaCAS::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Convocatorias CAS';
    protected static ?string $pluralModelLabel = 'Convocatorias CAS';
    protected static ?string $modelLabel = 'Convocatoria CAS';
    protected static ?string $recordTitleAttribute = 'proceso';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero_secuencial')
                    ->label('N° Correlativo del Proceso')
                    ->numeric()
                    ->required()
                    ->placeholder('Ej: 2')
                    ->helperText('Solo escribe el número. El sistema generará el formato CAS N°002-2026-MDL de forma automática.'),
                    
                TextInput::make('anio')
                    ->label('Año')
                    ->numeric()
                    ->required()
                    ->default(date('Y')),
                    
                DatePicker::make('fecha')
                    ->label('Fecha de Publicación')
                    ->required()
                    ->default(date('Y-m-d')),
                    
                Select::make('tipo')
                    ->label('Tipo de Documento')
                    ->options([
                        'Bases' => '📄 Bases de la Convocatoria',
                        'Resultados Preliminares' => '📊 Resultados Preliminares',
                        'Resultados Finales' => '🏆 Resultados Finales',
                        'Fe de Erratas' => '⚠️ Fe de Erratas',
                        'Comunicado' => '📢 Comunicado',

                    ])
                    ->required(),
                    
                TextInput::make('titulo')
                    ->label('Título de la Plaza / Cargo')
                    ->required()
                    ->placeholder('Ej: Especialista en Abastecimiento'),
                    
                FileUpload::make('pdf')
                    ->label('Documento PDF Real')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules(['mimes:pdf'])
                    ->disk('public')
                    ->directory('convocatorias-cas')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(
                        fn ($file) => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension()
                    )
                    ->required(),

                Select::make('status')
                    ->label('Estado Inicial')
                    ->options([
                        'Nuevo' => '🟢 Nuevo',
                        'Publicado' => '🔵 Publicado',
                        'Finalizado' => '🔴 Finalizado',
                    ])
                    ->default('Nuevo')
                    ->required(),
            ]);
    }

        public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // CORRECCIÓN: Se cambiaron los puntos (.) por flechas (->)
                TextColumn::make('codigo_cas')
                    ->label('Código')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('proceso')
                    ->label('Proceso')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('titulo')
                    ->label('Cargo / Título')
                    ->limit(30),
                    
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Nuevo' => 'success',
                        'Publicado' => 'info',
                        'Finalizado' => 'danger',
                        default => 'gray',
                    }),
                    
                TextColumn::make('pdf')
                    ->label('Documento')
                    ->formatStateUsing(fn () => '📄 Ver PDF')
                    ->url(fn ($record) => asset('storage/' . implode('/', array_map('rawurlencode', explode('/', $record->pdf)))), true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Acciones individuales
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array { return []; }

public static function getPages(): array
{
    return [
        'index' => \App\Filament\Resources\ConvocatoriaCAS\Pages\ListConvocatoriaCAS::route('/'),
        'create' => \App\Filament\Resources\ConvocatoriaCAS\Pages\CreateConvocatoriaCAS::route('/create'),
        'edit' => \App\Filament\Resources\ConvocatoriaCAS\Pages\EditConvocatoriaCAS::route('/{record}/edit'),
    ];
}

}
