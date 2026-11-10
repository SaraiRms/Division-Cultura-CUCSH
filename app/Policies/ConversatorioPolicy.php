<?php

namespace App\Policies;

use App\Models\Conversatorio;
use App\Models\User;

// Los conversatorios no pasan por revisión (el estado indica si es programado, en vivo o finalizado),
// Quienes lo gestionan son:
// administrador técnico, editor y responsable institucional.
class ConversatorioPolicy
{
    private function puedeGestionar(User $user): bool
    {
        return $user->can('editar_contenido_ajeno') || $user->can('publicar_contenido');
    }

    public function viewAny(User $user): bool { return $this->puedeGestionar($user); }
    public function view(User $user, Conversatorio $conversatorio): bool { return $this->puedeGestionar($user); }
    public function create(User $user): bool { return $this->puedeGestionar($user); }
    public function update(User $user, Conversatorio $conversatorio): bool { return $this->puedeGestionar($user); }
    public function delete(User $user, Conversatorio $conversatorio): bool { return $this->puedeGestionar($user); }
    public function deleteAny(User $user): bool { return $this->puedeGestionar($user); }
    public function restore(User $user, Conversatorio $conversatorio): bool { return $this->puedeGestionar($user); }
    public function restoreAny(User $user): bool { return $this->puedeGestionar($user); }
    public function forceDelete(User $user, Conversatorio $conversatorio): bool { return false; }
    public function forceDeleteAny(User $user): bool { return false; }
}