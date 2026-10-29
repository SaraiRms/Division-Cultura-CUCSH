<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

// Reglas de quién puede hacer qué con las noticias, según la matriz de permisos.
// Filament las consulta solo para mostrar u ocultar botones y páginas.
class NewsPolicy
{
    private function esPropia(User $user, News $news): bool
    {
        return $news->author_id === $user->id;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('crear_borradores')
            || $user->can('revisar_contenido')
            || $user->can('editar_contenido_ajeno');
    }

    public function view(User $user, News $news): bool
    {
        return $this->esPropia($user, $news)
            || $user->can('revisar_contenido')
            || $user->can('editar_contenido_ajeno');
    }

    public function create(User $user): bool
    {
        return $user->can('crear_borradores');
    }

    public function update(User $user, News $news): bool
    {
        // Contenido propio.
        if ($this->esPropia($user, $news) && $user->can('editar_contenido_propio')) {
            return true;
        }

        // Contenido de otras personas.
        if ($user->can('editar_contenido_ajeno')) {
            return true;
        }

        // Quien revisa puede abrir lo que está pendiente para aprobarlo o devolverlo.
        return $user->can('revisar_contenido') && $news->status === 'pending';
    }

    public function delete(User $user, News $news): bool
    {
        // Una noticia ya publicada solo la elimina quien tenga ese permiso.
        if ($news->status === 'published') {
            return $user->can('eliminar_contenido_publicado');
        }

        return $this->update($user, $news);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('eliminar_contenido_publicado')
            || $user->can('editar_contenido_ajeno')
            || $user->can('editar_contenido_propio');
    }

    public function restore(User $user, News $news): bool
    {
        return $this->delete($user, $news);
    }

    public function restoreAny(User $user): bool
    {
        return $this->deleteAny($user);
    }

    // No se borra definitivamente desde el panel: las noticias solo se mandan a la papelera.
    public function forceDelete(User $user, News $news): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
