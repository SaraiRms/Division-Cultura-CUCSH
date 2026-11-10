<?php

namespace App\Policies;

use App\Models\AcademicProgram;
use App\Models\User;

// Los programas los gestionan quienes pueden editar contenido ajeno o publicar:
// administrador técnico, editor y responsable institucional.

class AcademicProgramPolicy
{
    private function puedeGestionar(User $user): bool
    {
        return $user->can('editar_contenido_ajeno') || $user->can('publicar_contenido');
    }

    public function viewAny(User $user): bool { return $this->puedeGestionar($user); }
    public function view(User $user, AcademicProgram $program): bool { return $this->puedeGestionar($user); }
    public function create(User $user): bool { return $this->puedeGestionar($user); }
    public function update(User $user, AcademicProgram $program): bool { return $this->puedeGestionar($user); }
    public function delete(User $user, AcademicProgram $program): bool { return $this->puedeGestionar($user); }
    public function deleteAny(User $user): bool { return $this->puedeGestionar($user); }
    public function restore(User $user, AcademicProgram $program): bool { return false; }
    public function restoreAny(User $user): bool { return false; }
    public function forceDelete(User $user, AcademicProgram $program): bool { return false; }
    public function forceDeleteAny(User $user): bool { return false; }

}

// En caso de eliminar un programa, se eliminarán también sus contenidos relacionados (artículos, eventos, etc.) y sus archivos adjuntos. Por lo tanto, la eliminación de un programa es una acción irreversible.
// Así se define en la base de datos, con la opción "on delete cascade" en las relaciones. Por lo tanto, no es necesario implementar un método forceDelete() en esta política.