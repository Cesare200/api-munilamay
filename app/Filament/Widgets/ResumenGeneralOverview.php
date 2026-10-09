<?php

namespace App\Filament\Widgets;

use App\Models\AcuerdoConcejo;
use App\Models\ConvocatoriaCas;
use App\Models\Decreto;
use App\Models\Directiva;
use App\Models\DocInteres;
use App\Models\Gestion;
use App\Models\InstrumentoGestion;
use App\Models\InteraccionMercado;
use App\Models\OrdenanzaMunicipal;
use App\Models\ProcesoSeleccion;
use App\Models\ResolucionAlcaldia;
use App\Models\ResolucionGerencia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResumenGeneralOverview extends BaseWidget
{
    // Corregido: NO debe llevar static
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            // Resoluciones y Decretos
            Stat::make('Resoluciones de Alcaldía', class_exists(ResolucionAlcaldia::class) ? ResolucionAlcaldia::count() : 0)
                ->description('Actos administrativos de alcaldía')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->url('/admin/resolucion-alcaldias'),

            Stat::make('Resoluciones de Gerencia', class_exists(ResolucionGerencia::class) ? ResolucionGerencia::count() : 0)
                ->description('Gerencia municipal')
                ->descriptionIcon('heroicon-m-document-check')
                ->color('info')
                ->url('/admin/resolucion-gerencias'),

            Stat::make('Decretos', class_exists(Decreto::class) ? Decreto::count() : 0)
                ->description('Decretos emitidos')
                ->descriptionIcon('heroicon-m-document')
                ->color('success')
                ->url('/admin/decretos'),

            // Concejo y Normas
            Stat::make('Ordenanzas Municipales', class_exists(OrdenanzaMunicipal::class) ? OrdenanzaMunicipal::count() : 0)
                ->description('Normas de concejo distrital')
                ->descriptionIcon('heroicon-m-scale')
                ->color('warning')
                ->url('/admin/ordenanza-municipals'),

            Stat::make('Acuerdos de Concejo', class_exists(AcuerdoConcejo::class) ? AcuerdoConcejo::count() : 0)
                ->description('Sesiones y acuerdos')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->color('primary')
                ->url('/admin/acuerdo-concejos'),

            Stat::make('Directivas', class_exists(Directiva::class) ? Directiva::count() : 0)
                ->description('Disposiciones internas')
                ->descriptionIcon('heroicon-m-bookmark-square')
                ->color('gray')
                ->url('/admin/directivas'),

            // Contrataciones y Procesos
            Stat::make('Procesos de Selección', class_exists(ProcesoSeleccion::class) ? ProcesoSeleccion::count() : 0)
                ->description('Bases, convocatorias y actas')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('success')
                ->url('/admin/procesos-seleccions'),

            Stat::make('Interacción con el Mercado', class_exists(InteraccionMercado::class) ? InteraccionMercado::count() : 0)
                ->description('Esquelas y cotizaciones menores')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning')
                ->url('/admin/interaccion-mercados'),

            Stat::make('Convocatorias CAS', class_exists(ConvocatoriaCas::class) ? ConvocatoriaCas::count() : 0)
                ->description('Plazas y procesos de personal')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('danger')
                ->url('/admin/convocatoria-cas'),

            // Documentación Institucional
            Stat::make('Instrumentos de Gestión', class_exists(InstrumentoGestion::class) ? InstrumentoGestion::count() : 0)
                ->description('ROF, PAP, CAP, MCC')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info')
                ->url('/admin/instrumento-gestions'),

            Stat::make('Documentos de Interés', class_exists(DocInteres::class) ? DocInteres::count() : 0)
                ->description('Rendición y cuadros multianuales')
                ->descriptionIcon('heroicon-m-document-magnifying-glass')
                ->color('primary')
                ->url('/admin/doc-interes'),

            Stat::make('Gestión', class_exists(Gestion::class) ? Gestion::count() : 0)
                ->description('Documentos institucionales')
                ->descriptionIcon('heroicon-m-folder-open')
                ->color('gray')
                ->url('/admin/gestions'),
        ];
    }
}