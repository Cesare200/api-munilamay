<?php

namespace App\Filament\Resources\Directivas;

use App\Filament\Resources\Directivas\Pages\CreateDirectiva;
use App\Filament\Resources\Directivas\Pages\EditDirectiva;
use App\Filament\Resources\Directivas\Pages\ListDirectivas;
use App\Models\Directiva;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

use Filament\Actions\Action; 
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class DirectivaResource extends Resource
{
    protected static ?string $model = Directiva::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Directivas';
    protected static ?string $pluralModelLabel = 'Directivas';
    protected static ?string $modelLabel = 'Directiva';
    protected static ?string $recordTitleAttribute = 'titulo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titulo')
                    ->label('Título de la Directiva')
                    ->required()
                    ->placeholder('Ej: DIRECTIVA N° 006-2023-MDL'),
                    
                Textarea::make('descripcion')
                    ->label('Descripción / Asunto')
                    ->required()
                    ->rows(3)
                    ->placeholder('Escribe de qué trata la directiva...'),
                    
                DatePicker::make('fecha')
                    ->label('Fecha de Emisión')
                    ->required(),
                    
                FileUpload::make('pdf')
                    ->label('Documento PDF Real')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules(['mimes:pdf'])
                    ->disk('public')
                    ->directory('directivas')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(
                        fn ($file) => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension()
                    )
                    ->required(),

                Select::make('status')
                    ->label('Estado de Publicación')
                    ->options([
                        'borrador' => '📁 Borrador',
                        'publicado' => '🚀 Publicado',
                    ])
                    ->default('borrador')
                    ->required(),
            ]);
    }

        public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // CORRECCIÓN: Se cambiaron los puntos (.) por flechas (->)
                TextColumn::make('titulo')
                    ->label('Título')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(40)
                    ->searchable(),
                    
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
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    
                TextColumn::make('pdf')
                    ->label('Documento')
                    ->formatStateUsing(fn () => '📄 Ver PDF')
                    ->url(fn ($record) => asset('storage/' . implode('/', array_map('rawurlencode', explode('/', $record->pdf)))), true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
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
            'index' => \App\Filament\Resources\Directivas\Pages\ListDirectivas::route('/'),
            'create' => \App\Filament\Resources\Directivas\Pages\CreateDirectiva::route('/create'),
            'edit' => \App\Filament\Resources\Directivas\Pages\EditDirectiva::route('/{record}/edit'),
        ];
    }
}
