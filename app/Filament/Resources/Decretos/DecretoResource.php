<?php

namespace App\Filament\Resources\Decretos;

use App\Filament\Resources\Decretos\Pages;
use App\Models\Decreto;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DecretoResource extends Resource
{
    protected static ?string $model = Decreto::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Decretos';
    protected static ?string $modelLabel = 'Decreto';
    protected static ?string $pluralModelLabel = 'Decretos de Alcaldía';
    // Ikkatem wenno i-set iti null tapno maikkat iti grupo nga Administración:
    protected static string | UnitEnum | null $navigationGroup = null;

    // No kayatmo nga urnusen ti sumarunuan wenno posisionna iti menu:
    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('numero')
                ->label('Número de Decreto')
                ->placeholder('Ej: 001-2026-MDL')
                ->required()
                ->maxLength(100),

            TextInput::make('anio')
                ->label('Año')
                ->numeric()
                ->default(date('Y'))
                ->required(),

            DatePicker::make('fecha')
                ->label('Fecha de Emisión')
                ->required()
                ->default(now()),

            Toggle::make('status')
                ->label('Publicado / Activo')
                ->default(true)
                ->inline(false),

            FileUpload::make('pdf')
                ->label('Documento PDF del Decreto')
                ->directory('decretos')
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
                TextColumn::make('numero')
                    ->label('Número')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('anio')
                    ->label('Año')
                    ->sortable(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                IconColumn::make('status')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('pdf')
                    ->label('PDF')
                    ->formatStateUsing(fn ($state) => $state ? 'Ver PDF' : 'Sin archivo')
                    ->url(fn (Decreto $record) => $record->pdf ? asset('storage/' . $record->pdf) : null, true)
                    ->color(fn ($state) => $state ? 'primary' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('anio')
                    ->label('Filtrar por Año')
                    ->options(fn () => Decreto::query()->distinct()->pluck('anio', 'anio')->toArray()),

                Filter::make('solo_activos')
                    ->label('Solo Activos')
                    ->query(fn (Builder $query) => $query->where('status', true)),
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
            'index' => Pages\ListDecretos::route('/'),
            'create' => Pages\CreateDecreto::route('/create'),
            'edit' => Pages\EditDecreto::route('/{record}/edit'),
        ];
    }
}