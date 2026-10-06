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

// IMPORTAMOS EL RECURSO DE RESOLUCIONES MANUALMENTE
use App\Filament\Resources\ResolucionAlcaldias\ResolucionAlcaldiaResource;
use App\Filament\Resources\ConvocatoriaCAS\ConvocatoriaCASResource;
use App\Filament\Resources\ResolucionGerencias\ResolucionGerenciaResource; // <-- CON 's' AQUÍ
use App\Filament\Resources\Directivas\DirectivaResource; // <-- 1. IMPORTAR ARRIBA

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
            // Limpia los accesos de soporte por defecto en v5
            ->userMenuItems([]) 
            
            // REGISTRO MANUAL FORZADO PARA EVITAR ERRORES DE CARPETAS
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
                AccountWidget::class, // Mantiene la bienvenida de tu usuario
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
