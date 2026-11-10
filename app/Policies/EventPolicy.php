<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;


class EventPolicy
{
    private function esPropia(User $user, Event $event): bool
    {
        return $event->created_by === $user->id;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('crear_borradores')
            || $user->can('revisar_contenido')
            || $user->can('editar_contenido_ajeno');
    }

    public function view(User $user, Event $event): bool
    {
        return $this->esPropia($user, $event)
            || $user->can('revisar_contenido')
            || $user->can('editar_contenido_ajeno');
    }

    public function create(User $user): bool
    {
        return $user->can('crear_borradores');
    }

    public function update(User $user, Event $event): bool
    {

        if ($this->esPropia($user, $event) && $user->can('editar_contenido_propio')) {
            return true;
        }

        if ($user->can('editar_contenido_ajeno')) {
            return true;
        }

        return $user->can('revisar_contenido') && $event->status === 'draft';
    }

    public function delete(User $user, Event $event): bool
    {

        if ($event->status === 'published') {
            return $user->can('eliminar_contenido_publicado');
        }

        return $this->update($user, $event);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('eliminar_contenido_publicado')
            || $user->can('editar_contenido_ajeno')
            || $user->can('editar_contenido_propio');
    }

    public function restore(User $user, Event $event): bool
    {
        return $this->delete($user, $event);
    }

    public function restoreAny(User $user): bool
    {
        return $this->deleteAny($user);
    }

    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
