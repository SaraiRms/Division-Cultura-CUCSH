<?php

namespace App\Filament\Resources\DirectoryAdmins\Pages;

use App\Filament\Resources\DirectoryAdmins\DirectoryAdminResource;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Support\Eliminacion;

class EditDirectoryAdmin extends EditRecord
{
    protected static string $resource = DirectoryAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Eliminacion::eliminar('el', 'maestro', 'full_name'),
            Eliminacion::restaurar(),
            Eliminacion::eliminarDefinitivo(),
        ];
    }
}
