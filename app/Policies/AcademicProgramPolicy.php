<?php

namespace App\Policies;

// la usa la tabla de programas dentro de Departamentos
class AcademicProgramPolicy extends PermisoPolicy
{
    protected function permiso(): string { return 'gestionar_estructura'; }
}