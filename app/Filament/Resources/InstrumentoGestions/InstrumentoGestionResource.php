<?php

namespace App\Filament\Resources\InstrumentoGestions;

use App\Filament\Resources\InstrumentoGestions\Pages;
use App\Models\InstrumentoGestion;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InstrumentoGestionResource extends Resource
{
    protected static ?string $model = InstrumentoGestion::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Instrumentos de Gestión';
    protected static ?string $modelLabel = 'Instrumento de Gestión';
    protected static ?string $pluralModelLabel = 'Instrumentos de Gestión';
    protected static string | UnitEnum | null $navigationGroup = null; // Menú principal directo
    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tipo')
                ->label('Tipo de Instrumento')
                ->options([
                    'ROF' => 'ROF - Reglamento de Organización y Funciones',
                    'PAP' => 'PAP - Presupuesto Analítico de Personal',
                    'CAP' => 'CAP - Cuadro de Asignación de Personal',
                    'MCC' => 'MCC - Manual de Clasificador de Cargos',
                    'MOF' => 'MOF - Manual de Organización y Funciones',
                    'TUPA' => 'TUPA - Texto Único de Procedimientos Administrativos',
                    'POI' => 'POI - Plan Operativo Institucional',
                    'PEI' => 'PEI - Plan Estratégico Institucional',
                    'OTRO' => 'Otro Instrumento',
                ])
                ->searchable()
                ->required(),

            TextInput::make('nombre')
                ->label('Nombre del Instrumento')
                ->placeholder('Ej: Reglamento de Organización y Funciones')
                ->required()
                ->maxLength(255),

            TextInput::make('anio')
                ->label('Año')
                ->numeric()
                ->default(date('Y'))
                ->required(),

            DatePicker::make('fecha')
                ->label('Fecha de Aprobación/Emisión')
                ->required()
                ->default(now()),

            TextInput::make('aprobado_por')
                ->label('Norma de Aprobación')
                ->placeholder('Ej: Ordenanza Municipal N° 011-2025-MDL/C')
                ->maxLength(255)
                ->columnSpanFull(),

            Textarea::make('descripcion')
                ->label('Descripción / Resumen')
                ->rows(3)
                ->columnSpanFull(),

            Select::make('status')
                ->label('Estado')
                ->options([
                    'Publicado' => 'Publicado',
                    'Borrador' => 'Borrador',
                    'Derogado' => 'Derogado / Reemplazado',
                ])
                ->default('Publicado')
                ->required(),

            FileUpload::make('pdf')
                ->label('Documento PDF')
                ->directory('instrumentos-gestion')
                ->acceptedFileTypes(['application/pdf'])
                ->openable()
                ->downloadable()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('anio')
                    ->label('Año')
                    ->sortable(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('aprobado_por')
                    ->label('Aprobado Por')
                    ->toggleable()
                    ->limit(35),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Publicado' => 'success',
                        'Borrador' => 'warning',
                        'Derogado' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('pdf')
                    ->label('PDF')
                    ->formatStateUsing(fn ($state) => $state ? 'Ver PDF' : 'Sin archivo')
                    ->url(fn (InstrumentoGestion $record) => $record->pdf ? asset('storage/' . ltrim($record->pdf, '/')) : null, true)
                    ->color(fn ($state) => $state ? 'info' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('tipo')
                    ->label('Filtrar por Tipo')
                    ->options([
                        'ROF' => 'ROF',
                        'PAP' => 'PAP',
                        'CAP' => 'CAP',
                        'MCC' => 'MCC',
                        'MOF' => 'MOF',
                        'TUPA' => 'TUPA',
                        'POI' => 'POI',
                        'PEI' => 'PEI',
                    ]),

                SelectFilter::make('anio')
                    ->label('Filtrar por Año')
                    ->options(fn () => InstrumentoGestion::query()->distinct()->pluck('anio', 'anio')->toArray()),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('anio', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstrumentoGestions::route('/'),
            'create' => Pages\CreateInstrumentoGestion::route('/create'),
            'edit' => Pages\EditInstrumentoGestion::route('/{record}/edit'),
        ];
    }
}