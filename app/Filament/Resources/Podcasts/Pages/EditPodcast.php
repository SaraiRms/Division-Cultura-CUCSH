<?php

namespace App\Filament\Resources\Podcasts\Pages;

use App\Filament\Resources\Podcasts\PodcastResource;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Eliminacion;

class EditPodcast extends EditRecord
{
    protected static string $resource = PodcastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Eliminacion::eliminar('el', 'episodio'),
            Eliminacion::restaurar(),
            Eliminacion::eliminarDefinitivo(),
        ];
    }
}
