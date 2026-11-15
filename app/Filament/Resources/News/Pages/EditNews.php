<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Eliminacion;

class EditNews extends EditRecord
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Eliminacion::eliminar('la', 'noticia'),
            Eliminacion::restaurar(),
            Eliminacion::eliminarDefinitivo(),
        ];
    }
}
