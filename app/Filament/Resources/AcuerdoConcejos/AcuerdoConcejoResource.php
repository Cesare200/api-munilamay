<?php

namespace App\Filament\Resources\AcuerdoConcejos;

use App\Filament\Resources\AcuerdoConcejos\Pages\CreateAcuerdoConcejo;
use App\Filament\Resources\AcuerdoConcejos\Pages\EditAcuerdoConcejo;
use App\Filament\Resources\AcuerdoConcejos\Pages\ListAcuerdoConcejos;
use App\Models\acuerdo_concejo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class AcuerdoConcejoResource extends Resource
{
    protected static ?string $model = acuerdo_concejo::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Acuerdos de Concejo';
    protected static ?string $pluralModelLabel = 'Acuerdos de Concejo';
    protected static ?string $modelLabel = 'Acuerdo de Concejo';
    protected static ?string $recordTitleAttribute = 'numero';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('numero')->label('Número de Acuerdo')->numeric()->required()->placeholder('Ej: 12'),
                TextInput::make('anio')->label('Año')->numeric()->required()->placeholder('Ej: 2026'),
                DatePicker::make('fecha')->label('Fecha de Aprobación')->required(),
                FileUpload::make('pdf')
                    ->label('Documento PDF Original')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules(['mimes:pdf'])
                    ->disk('public')
                    ->directory('acuerdos-concejo')
                    ->visibility('public')
                    ->getUploadedFileNameForStorageUsing(
                        fn ($file) => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension()
                    )
                    ->required(),
                Select::make('status')
                    ->label('Estado de Publicación')
                    ->options(['borrador' => 'Borrador', 'publicado' => 'Publicado'])
                    ->default('borrador')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')->label('N° Acuerdo')->sortable()->searchable(),
                TextColumn::make('anio')->label('Año')->sortable(),
                TextColumn::make('fecha')->label('Fecha')->date('d/m/Y')->sortable(),
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
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcuerdoConcejos::route('/'),
            'create' => CreateAcuerdoConcejo::route('/create'),
            'edit' => EditAcuerdoConcejo::route('/{record}/edit'),
        ];
    }
}
