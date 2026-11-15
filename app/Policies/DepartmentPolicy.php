<?php
namespace App\Policies;

class DepartmentPolicy extends PermisoPolicy
{
    protected function permiso(): string { return 'gestionar_estructura'; }
}