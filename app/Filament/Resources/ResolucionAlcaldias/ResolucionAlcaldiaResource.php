<?php

namespace App\Filament\Resources\ResolucionAlcaldias;

use App\Filament\Resources\ResolucionAlcaldias\Pages\CreateResolucionAlcaldia;
use App\Filament\Resources\ResolucionAlcaldias\Pages\EditResolucionAlcaldia;
use App\Filament\Resources\ResolucionAlcaldias\Pages\ListResolucionAlcaldias;
use App\Models\ResolucionAlcaldia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; // Estándar de Filament v5
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

// Acciones unificadas de Filament v5
use Filament\Actions\Action; 
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ResolucionAlcaldiaResource extends Resource
{
    protected static ?string $model = ResolucionAlcaldia::class;

    // Icono oficial de Heroicons v5
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    // Etiquetas en español para tu barra lateral
    protected static ?string $navigationLabel = 'Resoluciones de Alcaldía';
    protected static ?string $pluralModelLabel = 'Resoluciones de Alcaldía';
    protected static ?string $modelLabel = 'Resolución de Alcaldía';
    protected static ?string $recordTitleAttribute = 'numero';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')
                    ->label('Número de Resolución')
                    ->numeric()
                    ->required()
                    ->placeholder('Ej: 45'),
                    
                TextInput::make('anio')
                    ->label('Año')
                    ->numeric()
                    ->required()
                    ->placeholder('Ej: 2026'),
                    
                DatePicker::make('fecha')
                    ->label('Fecha de Emisión')
                    ->required(),
                    
                FileUpload::make('pdf')
                    ->label('Documento PDF Real')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules(['mimes:pdf'])
                    ->disk('public')
                    ->directory('resoluciones')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(
                        fn ($file) => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension()
                    )
                    ->required(),

                Select::make('status')
                    ->label('Estado de Publicación')
                    ->options([
                        'borrador' => 'Borrador',
                        'publicado' => 'Publicado',
                    ])
                    ->default('borrador')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('N° Resolución')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('anio')
                    ->label('Año')
                    ->sortable(),
                    
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'borrador' => 'warning',
                        'publicado' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                    
                TextColumn::make('pdf')
                    ->label('Documento')
                    ->formatStateUsing(fn () => 'Ver PDF')
                    ->url(fn ($record) => asset('storage/' . implode('/', array_map('rawurlencode', explode('/', $record->pdf)))), true),
            ])
            ->filters([])
            ->recordActions([
                Action::make('edit')
                    ->label('Editar')
                    ->url(fn (ResolucionAlcaldia $record): string => route('filament.admin.resources.resolucion-alcaldias.edit', $record))
                    ->icon('heroicon-m-pencil-square')
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResolucionAlcaldias::route('/'),
            'create' => CreateResolucionAlcaldia::route('/create'),
            'edit' => EditResolucionAlcaldia::route('/{record}/edit'),
        ];
    }
}
