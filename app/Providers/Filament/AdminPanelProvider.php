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
use App\Filament\Resources\OrdenamientoTerritorials\OrdenamientoTerritorialResource; // <-- Nuevo Recurso Importado

// Página de login personalizada
use App\Filament\Pages\Auth\CustomLogin;

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
            
            // 1. Usar tu Login personalizado (solo uno aquí)
            ->login(CustomLogin::class)

            // 2. Identidad Institucional en Login y Panel
            ->brandName('Municipalidad Distrital de Lamay')
            ->brandLogo('https://munilamay.gob.pe/wp-content/uploads/2026/09/Escudo.png')
            ->brandLogoHeight('4rem')
            ->favicon('https://munilamay.gob.pe/wp-content/uploads/2026/09/Escudo.png')

            // 3. Colores Institucionales
            ->colors([
                'primary' => Color::hex('#063B00'),
                'warning' => Color::hex('#E1E100'),
            ])

            // Limpia los accesos de soporte por defecto
            ->userMenuItems([]) 
            
            // Registro manual de recursos
            ->resources([
                ResolucionAlcaldiaResource::class,
                ConvocatoriaCASResource::class,
                ResolucionGerenciaResource::class,
                DirectivaResource::class,
                OrdenamientoTerritorialResource::class, // <-- Módulo agregado
            ])
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                ResumenGeneralOverview::class,
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
            ]); // <-- El punto y coma va solo aquí al final de la cadena
    }
}