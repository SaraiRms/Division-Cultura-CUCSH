<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

// Los departamentos son fijos: se pueden ver y editar (descripción e imagen), pero no crea, ni borra.
// Los gestionan quienes pueden editar contenido ajeno o publicar:
// administrador técnico, editor y responsable institucional.

class DepartmentPolicy
{
    private function puedeGestionar(User $user): bool
    {
        return $user->can('editar_contenido_ajeno') || $user->can('publicar_contenido');
    }

    public function viewAny(User $user): bool { return $this->puedeGestionar($user); }
    public function view(User $user, Department $department): bool { return $this->puedeGestionar($user); }
    public function update(User $user, Department $department): bool { return $this->puedeGestionar($user); }

    public function create(User $user): bool { return false; }
    public function delete(User $user, Department $department): bool { return false; }
    public function deleteAny(User $user): bool { return false; }
    public function restore(User $user, Department $department): bool { return false; }
    public function restoreAny(User $user): bool { return false; }
    public function forceDelete(User $user, Department $department): bool { return false; }
    public function forceDeleteAny(User $user): bool { return false; }
    
}