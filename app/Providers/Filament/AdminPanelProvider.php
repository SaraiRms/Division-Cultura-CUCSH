<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
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
            ->profile()
            ->renderHook(PanelsRenderHook::FOOTER, fn () => auth()->check() ? view('filament.footer') : '') //footer
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => '<style>body.fi-body { background-color: #FAF8F7; }</style>') //color de fondo
            ->renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, fn () => auth()->check() ? '' : view('filament.login-lado'))
            ->renderHook(PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, fn () => view('filament.login-aviso'))

            // se coloca el color predeterminado para botones y menú
            ->colors([
                'primary' => '#781d26', //Color vino
            ])

            // se define el tipo de fuente para el panel, se puede cambiar a cualquier fuente de Google Fonts
            
            ->brandName('División de Estudios de la Cultura')
            ->brandLogo(asset('images/Logo_Div_Cultura.svg'))
            ->favicon(asset('favicon.ico'))
            ->brandLogoHeight('3rem')
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            
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
