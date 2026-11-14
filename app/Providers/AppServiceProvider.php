<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('aportaciones', function (Request $request) {
            return [
                Limit::perHour(10)->by($request->ip()),                                    // por conexión
                Limit::perDay(5)->by(strtolower((string) $request->input('contact_email'))), // por correo
            ];
        });
}
}
