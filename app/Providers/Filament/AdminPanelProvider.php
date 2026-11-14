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
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->renderHook(PanelsRenderHook::FOOTER, fn () => view('filament.footer')) //footer
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => '<style>body.fi-body { background-color: #FBF8F5; }</style>') //color de fondo

            // se coloca el color predeterminado para botones y menú
            ->colors([
                'primary' => '#781d26', //Color vino
            ])

            // se define el tipo de fuente para el panel, se puede cambiar a cualquier fuente de Google Fonts
            
            ->brandName('División de Estudios de la Cultura')
            ->brandLogo(asset('images/Logo_Div_Cultura.svg'))
            ->brandLogoHeight('3rem')
            ->darkMode(false)                    // sin modo oscuro: la marca es clara
            ->sidebarCollapsibleOnDesktop()
            // ->renderHook(PanelsRenderHook::SIDEBAR_NAV_START, fn () => view('filament.logo-colapsado'))
            
            /////////////////////////
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            
            ->widgets([
                AccountWidget::class, //Saludo con botón para cerrar cesión
                \App\Filament\Widgets\ResumenStats::class
                ])



            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

            
    }
}
