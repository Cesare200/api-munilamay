<?php

namespace App\Filament\Resources\AcuerdoConcejos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AcuerdoConcejosTable
{
    public static function configure(Table $table): Table
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
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
