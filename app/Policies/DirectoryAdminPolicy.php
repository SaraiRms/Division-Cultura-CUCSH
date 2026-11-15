<?php

namespace App\Policies;

//directorio de maestros
class DirectoryAdminPolicy extends PermisoPolicy
{
    protected function permiso(): string { return 'gestionar_directorio'; }
}