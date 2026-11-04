<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Resources\ConvocatoriaCAS\ConvocatoriaCASResource;
use App\Filament\Resources\Directivas\DirectivaResource;
use App\Filament\Resources\OrdenamientoTerritorials\OrdenamientoTerritorialResource;
use App\Filament\Resources\ResolucionAlcaldias\ResolucionAlcaldiaResource;
use App\Filament\Resources\ResolucionGerencias\ResolucionGerenciaResource;
use App\Filament\Widgets\ResumenGeneralOverview;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
// Recursos manuales registrados
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings; // <-- Nuevo Recurso Importado
// Página de login personalizada
use Illuminate\Session\Middleware\StartSession;
// Widget del Dashboard con todas las estadísticas
use Illuminate\View\Middleware\ShareErrorsFromSession;

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
            ->brandLogo(asset('assets/images/lamay-logo.png'))
            ->brandLogoHeight('4rem')
            ->favicon(asset('assets/images/lamay-logo.png'))

            // 3. Colores Institucionales
            ->colors([
                'primary' => Color::hex('#063B00'),
                'warning' => Color::hex('#E1E100'),
            ])

            ->userMenuItems([
                'profile' => fn (Action $action): Action => $action->label('Mi cuenta'),
            ])
            ->profile(isSimple: false)

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
