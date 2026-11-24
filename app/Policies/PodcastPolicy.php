<?php

namespace App\Policies;

use App\Models\Podcast;
use App\Models\User;

// El podcast no tiene paso de revisión (el estado solo indica si está publicado), lo gestionan
// quienes pueden editar contenido ajeno o publicar: administrador técnico, editor y responsable institucional.

class PodcastPolicy
{
    private function puedeGestionar(User $user): bool
    {
        return $user->can('editar_contenido_ajeno') || $user->can('publicar_contenido');
    }

    public function viewAny(User $user): bool { return $this->puedeGestionar($user); }
    public function view(User $user, Podcast $podcast): bool { return $this->puedeGestionar($user); }
    public function create(User $user): bool { return $this->puedeGestionar($user); }
    public function update(User $user, Podcast $podcast): bool { return $this->puedeGestionar($user); }


    public function delete(User $user, Podcast $podcast): bool
    {
        return $user->can('eliminar_contenido_publicado');
    }
    public function deleteAny(User $user): bool
    {
        return $user->can('eliminar_contenido_publicado');
    }

    // Restaurar y eliminar definitivamente: con el permiso gestionar_papelera.
    public function restore(User $user, Podcast $podcast): bool { return $user->can('gestionar_papelera'); }
    public function restoreAny(User $user): bool { return $user->can('gestionar_papelera'); }
    public function forceDelete(User $user, Podcast $podcast): bool { return $user->can('gestionar_papelera'); }
    public function forceDeleteAny(User $user): bool { return $user->can('gestionar_papelera'); }
    
}