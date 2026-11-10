<?php

namespace App\Policies;

use App\Models\DirectoryRectoria;
use App\Models\User;

// El directorio no requiere de permisos especiales.
// Por eso solo lo gestionan quienes pueden editar contenido ajeno o publicar:
// *Administrador técnico*, *Editor* y *Responsable institucional*.

class DirectoryRectoriaPolicy
{
    private function puedeGestionar(User $user): bool
    {
        return $user->can('editar_contenido_ajeno') || $user->can('publicar_contenido');
    }

    public function viewAny(User $user): bool { return $this->puedeGestionar($user); }
    public function view(User $user, DirectoryRectoria $directoryRectoria): bool { return $this->puedeGestionar($user); }
    public function create(User $user): bool { return $this->puedeGestionar($user); }
    public function update(User $user, DirectoryRectoria $directoryRectoria): bool { return $this->puedeGestionar($user); }
    public function delete(User $user, DirectoryRectoria $directoryRectoria): bool { return $this->puedeGestionar($user); }
    public function deleteAny(User $user): bool { return $this->puedeGestionar($user); }
    public function restore(User $user, DirectoryRectoria $directoryRectoria): bool { return $this->puedeGestionar($user); }
    public function restoreAny(User $user): bool { return $this->puedeGestionar($user); }
    public function forceDelete(User $user, DirectoryRectoria $directoryRectoria): bool { return false; }
    public function forceDeleteAny(User $user): bool { return false; }

}