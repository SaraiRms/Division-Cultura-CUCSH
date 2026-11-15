<?php

namespace App\Policies;

class DirectoryRectoriaPolicy extends PermisoPolicy
{
    protected function permiso(): string { return 'gestionar_directorio'; }
}