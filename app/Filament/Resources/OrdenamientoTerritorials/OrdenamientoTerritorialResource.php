<?php

namespace App\Filament\Resources\OrdenamientoTerritorials;

use App\Filament\Resources\OrdenamientoTerritorials\Pages;
use App\Models\OrdenamientoTerritorial;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class OrdenamientoTerritorialResource extends Resource
{
    protected static ?string $model = OrdenamientoTerritorial::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-map';
    protected static UnitEnum|string|null $navigationGroup = 'Gestión Territorial';
    protected static ?string $navigationLabel = 'Ordenamiento Territorial';
    protected static ?string $pluralModelLabel = 'Documentos de Ordenamiento Territorial';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Section::make('Información del Documento / Mapa')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título del Documento / Mapa')
                            ->required()
                            ->placeholder('Ej: Tomo 1: Diagnóstico (2026 - 2036)'),

                        Forms\Components\Select::make('categoria')
                            ->label('Categoría / Tomo')
                            ->options([
                                'tomo_1_diagnostico' => 'Tomo 1: Diagnóstico (2026 - 2036)',
                                'tomo_2_propuestas'  => 'Tomo 2: Propuestas Generales / Específicas / Gestión',
                                'tomo_3_reglamento'  => 'Tomo 3: Reglamento',
                                'mapa_diagnostico'   => 'Mapa de Diagnóstico',
                                'mapa_propuesta'     => 'Mapa de Propuesta',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('subcategoria')
                            ->label('Subcategoría (Opcional)')
                            ->placeholder('Ej: Propuestas Específicas / Programa de Inversiones')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción breve')
                            ->rows(3),

                        Forms\Components\FileUpload::make('archivo_pdf')
                            ->label('Archivo PDF')
                            ->directory('ordenamiento-territorial')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(20480)
                            ->downloadable()
                            ->openable()
                            ->required(),

                        Forms\Components\Toggle::make('estado')
                            ->label('Publicado en la Web')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoría')
                    ->badge()
                    ->colors([
                        'primary'   => 'tomo_1_diagnostico',
                        'success'   => 'tomo_2_propuestas',
                        'warning'   => 'tomo_3_reglamento',
                        'info'      => 'mapa_diagnostico',
                        'secondary' => 'mapa_propuesta',
                    ]),

                Tables\Columns\IconColumn::make('estado')
                    ->label('Publicado')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')
                    ->options([
                        'tomo_1_diagnostico' => 'Tomo 1: Diagnóstico',
                        'tomo_2_propuestas'  => 'Tomo 2: Propuestas',
                        'tomo_3_reglamento'  => 'Tomo 3: Reglamento',
                        'mapa_diagnostico'   => 'Mapas de Diagnóstico',
                        'mapa_propuesta'     => 'Mapas de Propuestas',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrdenamientoTerritorials::route('/'),
            'create' => Pages\CreateOrdenamientoTerritorial::route('/create'),
            'edit'   => Pages\EditOrdenamientoTerritorial::route('/{record}/edit'),
        ];
    }
}