<?php

namespace App\Filament\Resources\Graduates\Pages;

use App\Filament\Resources\Graduates\GraduateResource;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Eliminacion;

class EditGraduate extends EditRecord
{
    protected static string $resource = GraduateResource::class;

    protected function getHeaderActions(): array
    {
        return [
        Eliminacion::eliminar('la', 'generación'),
        Eliminacion::restaurar(),
        Eliminacion::eliminarDefinitivo(),
        ];
    }
}
