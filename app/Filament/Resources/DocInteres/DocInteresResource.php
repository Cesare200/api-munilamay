<?php

namespace App\Filament\Resources\DocInteres;

use App\Filament\Resources\DocInteres\Pages;
use App\Models\DocInteres;
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

class DocInteresResource extends Resource
{
    protected static ?string $model = DocInteres::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationLabel = 'Documentos de Interés';
    protected static ?string $modelLabel = 'Documento de Interés';
    protected static ?string $pluralModelLabel = 'Documentos de Interés';
    protected static string | UnitEnum | null $navigationGroup = null; // Menú directo
    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('titulo')
                ->label('Título del Documento')
                ->placeholder('Ej: APROBACIÓN DE CUADRO MULTIANUAL DE NECESIDADES...')
                ->required()
                ->maxLength(191)
                ->columnSpanFull(),

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

            Textarea::make('descripcion')
                ->label('Descripción / Resumen')
                ->rows(4)
                ->required()
                ->columnSpanFull(),

            FileUpload::make('pdf')
                ->label('Documento PDF')
                ->directory('docinteres')
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
                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
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
                    ->label('Archivo')
                    ->formatStateUsing(fn ($state) => $state ? 'Ver PDF' : 'Sin archivo')
                    ->url(fn (DocInteres $record) => $record->pdf ? asset('storage/' . ltrim($record->pdf, '/')) : null, true)
                    ->color(fn ($state) => $state ? 'info' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
            'index' => Pages\ListDocInteres::route('/'),
            'create' => Pages\CreateDocInteres::route('/create'),
            'edit' => Pages\EditDocInteres::route('/{record}/edit'),
        ];
    }
}