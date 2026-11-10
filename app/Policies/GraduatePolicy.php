<?php

namespace App\Policies;

use App\Models\Graduate;
use App\Models\User;

// Reglas de quién puede hacer qué con las noticias, según la matriz de permisos.
// Filament las consulta solo para mostrar u ocultar botones y páginas.
class GraduatePolicy
{
    private function esPropia(User $user, Graduate $graduate): bool
    {
        return $graduate->uploaded_by === $user->id;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('crear_borradores')
            || $user->can('revisar_contenido')
            || $user->can('editar_contenido_ajeno');
    }

    public function view(User $user, Graduate $graduate): bool
    {
        return $this->esPropia($user, $graduate)
            || $user->can('revisar_contenido')
            || $user->can('editar_contenido_ajeno');
    }

    public function create(User $user): bool
    {
        return $user->can('crear_borradores');
    }

    public function update(User $user, Graduate $graduate): bool
    {
        // Contenido propio.
        if ($this->esPropia($user, $graduate) && $user->can('editar_contenido_propio')) {
            return true;
        }

        // Contenido de otras personas.
        if ($user->can('editar_contenido_ajeno')) {
            return true;
        }

        // Quien revisa puede abrir lo que está pendiente para aprobarlo o devolverlo.
        return $user->can('revisar_contenido') && $graduate->status === 'pending';
    }

    public function delete(User $user, Graduate $graduate): bool
    {
        // Una noticia ya publicada solo la elimina quien tenga ese permiso.
        if ($graduate->status === 'published') {
            return $user->can('eliminar_contenido_publicado');
        }

        return $this->update($user, $graduate);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('eliminar_contenido_publicado')
            || $user->can('editar_contenido_ajeno')
            || $user->can('editar_contenido_propio');
    }

    public function restore(User $user, Graduate $graduate): bool
    {
        return $this->delete($user, $graduate);
    }

    public function restoreAny(User $user): bool
    {
        return $this->deleteAny($user);
    }

    // No se borra definitivamente desde el panel: las noticias solo se mandan a la papelera.
    public function forceDelete(User $user, Graduate $graduate): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
