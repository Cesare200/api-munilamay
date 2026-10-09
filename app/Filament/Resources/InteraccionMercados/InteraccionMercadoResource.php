<?php

namespace App\Filament\Resources\InteraccionMercados;

use App\Filament\Resources\InteraccionMercados\Pages;
use App\Models\InteraccionMercado;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InteraccionMercadoResource extends Resource
{
    protected static ?string $model = InteraccionMercado::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Interacción con el Mercado';
    protected static ?string $modelLabel = 'Interacción con el Mercado';
    protected static ?string $pluralModelLabel = 'Interacciones con el Mercado';
    protected static string | UnitEnum | null $navigationGroup = null;
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('numero')
                ->label('N° de Esquela / Convocatoria')
                ->placeholder('Ej: 004-2025')
                ->maxLength(50),

            TextInput::make('anio')
                ->label('Año')
                ->numeric()
                ->default(date('Y'))
                ->required(),

            DatePicker::make('fecha')
                ->label('Fecha de Publicación')
                ->required()
                ->default(now()),

            Select::make('status')
                ->label('Estado')
                ->options([
                    'publicado' => 'Publicado',
                    'borrador' => 'Borrador',
                ])
                ->default('publicado')
                ->required(),

            Textarea::make('titulo')
                ->label('Título / Objeto de Contratación')
                ->placeholder('Ej: ESQUELA DE PUBLICACIÓN N° 004-2025: ...')
                ->required()
                ->rows(3)
                ->columnSpanFull(),

            Textarea::make('descripcion')
                ->label('Descripción / Detalle adicional')
                ->rows(3)
                ->columnSpanFull(),

            FileUpload::make('pdf')
                ->label('Documento PDF de la Esquela')
                ->directory('interaccion-mercado')
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(512000)
                ->openable()
                ->downloadable()
                ->required()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Esquela')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('titulo')
                    ->label('Objeto de Contratación')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('anio')
                    ->label('Año')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'publicado' => 'success',
                        'borrador' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('pdf')
                    ->label('Documento')
                    ->formatStateUsing(fn () => 'Ver PDF')
                    ->url(fn (InteraccionMercado $record) => asset('storage/' . ltrim($record->pdf, '/')), true)
                    ->color('primary'),
            ])
            ->filters([
                SelectFilter::make('anio')
                    ->label('Filtrar por Año')
                    ->options(fn () => InteraccionMercado::query()->distinct()->pluck('anio', 'anio')->toArray()),

                SelectFilter::make('status')
                    ->label('Filtrar por Estado')
                    ->options([
                        'publicado' => 'Publicado',
                        'borrador' => 'Borrador',
                    ]),
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
            ->defaultSort('fecha', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInteraccionMercados::route('/'),
            'create' => Pages\CreateInteraccionMercado::route('/create'),
            'edit' => Pages\EditInteraccionMercado::route('/{record}/edit'),
        ];
    }
}