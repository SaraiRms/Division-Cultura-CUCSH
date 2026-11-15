<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Eliminacion;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Eliminacion::eliminar('el', 'evento'),
        Eliminacion::restaurar(),
        Eliminacion::eliminarDefinitivo(),
        ];
    }
}
