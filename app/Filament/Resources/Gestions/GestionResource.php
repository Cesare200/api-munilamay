<?php

namespace App\Filament\Resources\Gestions;

use App\Filament\Resources\Gestions\Pages;
use App\Models\Gestion;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GestionResource extends Resource
{
    protected static ?string $model = Gestion::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Gestión';
    protected static ?string $modelLabel = 'Gestión';
    protected static ?string $pluralModelLabel = 'Gestión Institucional';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Detalles de Gestión')
                ->tabs([
                    Tab::make('Alcalde')
                        ->schema([
                            TextInput::make('alcalde.nombre')
                                ->label('Nombre del Alcalde')
                                ->required(),
                            TextInput::make('alcalde.cargo')
                                ->label('Cargo')
                                ->default('Alcalde Distrital'),
                            FileUpload::make('alcalde.foto')
                                ->label('Foto del Alcalde')
                                ->image()
                                ->directory('gestion/alcalde'),
                            Textarea::make('alcalde.mensaje')
                                ->label('Mensaje de Bienvenida')
                                ->rows(4),
                        ]),

                    Tab::make('Concejo Municipal')
                        ->schema([
                            Repeater::make('concejo')
                                ->label('Miembros del Concejo (Regidores)')
                                ->schema([
                                    TextInput::make('nombre')
                                        ->required(),
                                    TextInput::make('cargo')
                                        ->default('Regidor(a)')
                                        ->required(),
                                    FileUpload::make('foto')
                                        ->image()
                                        ->directory('gestion/concejo'),
                                ])
                                ->columns(3)
                                ->defaultItems(1),
                        ]),

                    Tab::make('Identidad e Historia')
                        ->schema([
                            TextInput::make('eslogan')
                                ->label('Eslogan Institucional'),
                            Textarea::make('mision')
                                ->label('Misión')
                                ->rows(3),
                            Textarea::make('vision')
                                ->label('Visión')
                                ->rows(3),
                            RichEditor::make('historia')
                                ->label('Reseña Histórica'),
                        ]),

                    Tab::make('Galería de Fotos')
                        ->schema([
                            FileUpload::make('fotos_gestion')
                                ->label('Fotografías de la Gestión')
                                ->multiple()
                                ->image()
                                ->directory('gestion/galeria')
                                ->reorderable(),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('alcalde.nombre')->label('Alcalde'),
                TextColumn::make('eslogan')->label('Eslogan'),
                TextColumn::make('updated_at')->label('Última Actualización')->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGestions::route('/'),
            'create' => Pages\CreateGestion::route('/create'),
            'edit' => Pages\EditGestion::route('/{record}/edit'),
        ];
    }
}