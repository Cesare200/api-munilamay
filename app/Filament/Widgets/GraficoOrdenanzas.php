<?php

namespace App\Filament\Widgets;

use App\Models\Ordenanza;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GraficoOrdenanzas extends ChartWidget
{
    // Propiedades comunes sin 'static' para Filament v5
    protected ?string $heading = 'Ordenanzas Emitidas por Año';

    protected ?string $pollingInterval = '10s';

    protected function getData(): array
    {
        // Agrupa tus registros de MySQL por la columna 'anio'
        $data = Ordenanza::select('anio', DB::raw('count(*) as total'))
            ->groupBy('anio')
            ->orderBy('anio', 'asc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de Ordenanzas',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#ff7300', 
                    'borderColor' => '#ff5100',
                ],
            ],
            'labels' => $data->pluck('anio')->toArray(), // Años en la base del gráfico
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Renderiza barras verticales
    }
}
