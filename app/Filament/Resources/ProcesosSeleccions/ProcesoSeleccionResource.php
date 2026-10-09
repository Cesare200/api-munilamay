<?php

namespace App\Filament\Resources\ProcesosSeleccions;

use App\Filament\Resources\ProcesosSeleccions\Pages;
use App\Models\ProcesoSeleccion;
use BackedEnum;
use Carbon\Carbon;
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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProcesoSeleccionResource extends Resource
{
    protected static ?string $model = ProcesoSeleccion::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Procesos de Selección';
    protected static ?string $modelLabel = 'Proceso de Selección';
    protected static ?string $pluralModelLabel = 'Procesos de Selección';
    protected static string | UnitEnum | null $navigationGroup = null;
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('year')
                ->label('Año del Proceso')
                ->numeric()
                ->default(date('Y'))
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                    $num = $get('number');
                    if ($state && $num) {
                        $padded = str_pad(ltrim($num, '0'), 3, '0', STR_PAD_LEFT);
                        $set('filter', "{$state}-{$padded}");
                    }
                }),

            TextInput::make('number')
                ->label('N° Convocatoria (3 dígitos)')
                ->placeholder('Ej: 001 o 1')
                ->required()
                ->maxLength(10)
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                    $year = $get('year') ?? date('Y');
                    if ($state) {
                        $padded = str_pad(ltrim($state, '0'), 3, '0', STR_PAD_LEFT);
                        $set('number', $padded);
                        $set('filter', "{$year}-{$padded}");
                    }
                }),

            TextInput::make('filter')
                ->label('Código Filtro (Automático)')
                ->readOnly()
                ->placeholder('Ej: 2025-004')
                ->helperText('Se autogenera con el Año y Número'),

            DatePicker::make('date')
                ->label('Fecha del Documento')
                ->required()
                ->default(now())
                ->live()
                ->afterStateUpdated(function (Set $set, ?string $state) {
                    if ($state) {
                        $dt = Carbon::parse($state)->locale('es');
                        $set('date_label', 'Publicado: ' . $dt->translatedFormat('d \d\e F - Y'));
                    }
                }),

            TextInput::make('date_label')
                ->label('Etiqueta de Fecha (Automática)')
                ->readOnly()
                ->columnSpanFull(),

            TextInput::make('title')
                ->label('Título del Documento')
                ->placeholder('Ej: BASES INTEGRADAS')
                ->required()
                ->columnSpanFull(),

            Textarea::make('description')
                ->label('Descripción / Detalle')
                ->rows(3)
                ->columnSpanFull(),

            FileUpload::make('file')
                ->label('Archivo (PDF o Word)')
                ->directory('procesos-seleccion')
                ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->maxSize(512000)
                ->openable()
                ->downloadable()
                ->required()
                ->live()
                ->afterStateUpdated(function (Set $set, $state) {
                    if ($state) {
                        $origName = is_string($state) ? $state : $state->getClientOriginalName();
                        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                        $set('file_type', $ext ?: 'pdf');
                    }
                })
                ->columnSpan(2),

            TextInput::make('file_type')
                ->label('Tipo de Archivo')
                ->readOnly()
                ->default('pdf'),

            Select::make('status')
                ->label('Estado')
                ->options([
                    'publicado' => 'Publicado',
                    'borrador' => 'Borrador',
                ])
                ->default('publicado')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('filter')
                    ->label('Proceso')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('file_type')
                    ->label('Formato')
                    ->badge()
                    ->color(fn ($state) => $state === 'pdf' ? 'danger' : 'info')
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'publicado' => 'success',
                        'borrador' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('file')
                    ->label('Descargar')
                    ->formatStateUsing(fn () => 'Descargar')
                    ->url(fn (ProcesoSeleccion $record) => asset('storage/' . ltrim($record->file, '/')), true)
                    ->color('primary'),
            ])
            ->filters([
                SelectFilter::make('year')
                    ->label('Filtrar por Año')
                    ->options(fn () => ProcesoSeleccion::query()->distinct()->pluck('year', 'year')->toArray()),

                SelectFilter::make('filter')
                    ->label('Filtrar por Proceso')
                    ->options(fn () => ProcesoSeleccion::query()->distinct()->pluck('filter', 'filter')->toArray()),
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
            ->defaultSort('date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcesosSeleccions::route('/'),
            'create' => Pages\CreateProcesoSeleccion::route('/create'),
            'edit' => Pages\EditProcesoSeleccion::route('/{record}/edit'),
        ];
    }
}