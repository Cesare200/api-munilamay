<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// Recursos manuales registrados
use App\Filament\Resources\ResolucionAlcaldias\ResolucionAlcaldiaResource;
use App\Filament\Resources\ConvocatoriaCAS\ConvocatoriaCASResource;
use App\Filament\Resources\ResolucionGerencias\ResolucionGerenciaResource;
use App\Filament\Resources\Directivas\DirectivaResource;

// Widget del Dashboard con todas las estadísticas
use App\Filament\Widgets\ResumenGeneralOverview;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            // Limpia los accesos de soporte por defecto
            ->userMenuItems([]) 
            
            // Registro manual de recursos
            ->resources([
                ResolucionAlcaldiaResource::class,
                ConvocatoriaCASResource::class,
                ResolucionGerenciaResource::class,
                DirectivaResource::class,
            ])
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,           // Mantiene la bienvenida de tu usuario
                ResumenGeneralOverview::class,  // Muestra todas las tarjetas y estadísticas de los 12 módulos
            ])
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}