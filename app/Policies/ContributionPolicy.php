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

    public function delete(User $user, Contribution $contribution): bool
    {
        return false; // solo el administrador (lo resuelve Gate::before)
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

    // Restaurar y eliminar definitivamente: solo el administrador (lo resuelve Gate::before).
    public function restore(User $user, Contribution $contribution): bool { return false; }
    public function restoreAny(User $user): bool { return false; }
    public function forceDelete(User $user, Contribution $contribution): bool { return false; }
    public function forceDeleteAny(User $user): bool { return false; }

}
