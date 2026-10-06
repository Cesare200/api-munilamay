<?php

namespace App\Filament\Widgets;

use App\Models\Ordenanza;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContadorOrdenanzas extends BaseWidget
{
    // Propiedad común sin 'static' para Filament v5
    protected ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Ordenanzas', Ordenanza::count())
                ->description('Todas las ordenanzas registradas')
                ->descriptionIcon('heroicon-m-folder')
                ->color('info'),

            Stat::make('Publicadas', Ordenanza::where('status', 'publicado')->count())
                ->description('Visibles en la API pública')
                ->descriptionIcon('heroicon-m-rocket-launch')
                ->color('success'),

            Stat::make('En Borrador', Ordenanza::where('status', 'borrador')->count())
                ->description('En revisión interna')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
        ];
    }
}
