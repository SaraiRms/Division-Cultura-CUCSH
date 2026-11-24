<?php

namespace App\Policies;

use App\Models\Contribution;
use App\Models\User;

class ContributionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('revisar_contenido');
    }

    public function view(User $user, Contribution $contribution): bool
    {
        return $user->can('revisar_contenido');
    }

    // Solo llegan desde el formulario público
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Contribution $contribution): bool
    {
        if ($user->can('publicar_contenido')) {
            return true;
        }

        // Quien solo revisa no toca lo que ya está publicado
        return $user->can('revisar_contenido') && $contribution->status !== 'published';
    }

    // Borrar una aportación es definitivo: va con el permiso de la papelera.
    public function delete(User $user, Contribution $contribution): bool
    {
        return $user->can('gestionar_papelera');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('gestionar_papelera');
    }

    // Restaurar y eliminar definitivamente: con el permiso gestionar_papelera.
    public function restore(User $user, Contribution $contribution): bool { return $user->can('gestionar_papelera'); }
    public function restoreAny(User $user): bool { return $user->can('gestionar_papelera'); }
    public function forceDelete(User $user, Contribution $contribution): bool { return $user->can('gestionar_papelera'); }
    public function forceDeleteAny(User $user): bool { return $user->can('gestionar_papelera'); }

}
