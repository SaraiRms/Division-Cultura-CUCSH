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
        // Quien puede editar lo de otras personas, puede editar todo.
        if ($user->can('editar_contenido_ajeno')) {
            return true;
        }

        // Contenido propio: solo mientras es borrador o fue devuelto para corregir.
        if ($this->esPropia($user, $event) && $user->can('editar_contenido_propio')) {
            return in_array($event->status, ['draft', 'rejected'], true);
        }

        // Quien revisa puede abrir lo que está pendiente para aprobarlo o devolverlo.
        return $user->can('revisar_contenido') && $event->status === 'pending';
    }
    
    public function delete(User $user, Event $event): bool
    {
        return $user->can('eliminar_contenido_publicado');
    }
    public function deleteAny(User $user): bool
    {
        return $user->can('eliminar_contenido_publicado');
    }

    public function restore(User $user, Event $event): bool { return $user->can('gestionar_papelera'); }
    public function restoreAny(User $user): bool { return $user->can('gestionar_papelera'); }
    public function forceDelete(User $user, Event $event): bool { return $user->can('gestionar_papelera'); }
    public function forceDeleteAny(User $user): bool { return $user->can('gestionar_papelera'); }

}
