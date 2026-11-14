<?php

namespace App\Filament\Widgets;

use App\Models\Contribution;
use App\Models\Event;
use App\Models\News;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResumenStats extends StatsOverviewWidget
{
    // Solo lo ve quien puede revisar contenido (no el auxiliar)
    public static function canView(): bool
    {
        return auth()->user()?->can('revisar_contenido') ?? false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Noticias por revisar', News::where('status', 'pending')->count())
                ->description('Esperan autorización')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('warning'),

            Stat::make('Noticias publicadas', News::published()->count())
                ->description('Visibles en la página')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),

            Stat::make('Próximos eventos', Event::upcoming()->count())
                ->description('De hoy en adelante')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('primary'),

            Stat::make('Aportaciones por revisar', Contribution::where('status', 'received')->count())
                ->description('Recibidas desde el formulario')
                ->descriptionIcon(Heroicon::OutlinedInboxArrowDown)
                ->color('info'),
        ];
    }
}
