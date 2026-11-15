<?php

namespace App\Filament\Resources\DirectoryRectorias\Pages;

use App\Filament\Resources\DirectoryRectorias\DirectoryRectoriaResource;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Eliminacion;

class EditDirectoryRectoria extends EditRecord
{
    protected static string $resource = DirectoryRectoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Eliminacion::eliminar('el', 'integrante de Rectoría', 'full_name'),
            Eliminacion::restaurar(),
            Eliminacion::eliminarDefinitivo(),
        ];
    }
}
