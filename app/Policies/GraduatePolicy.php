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
        // Quien puede editar lo de otras personas, puede editar todo.
        if ($user->can('editar_contenido_ajeno')) {
            return true;
        }

        // Contenido propio: solo mientras es borrador o fue devuelto para corregir.
        if ($this->esPropia($user, $graduate) && $user->can('editar_contenido_propio')) {
            return in_array($graduate->status, ['draft', 'rejected'], true);
        }

        // Quien revisa puede abrir lo que está pendiente para aprobarlo o devolverlo.
        return $user->can('revisar_contenido') && $graduate->status === 'pending';
    }


    public function delete(User $user, Graduate $graduate): bool
    {
        return $user->can('eliminar_contenido_publicado');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('eliminar_contenido_publicado');
    }

    // Restaurar y eliminar definitivamente: con el permiso gestionar_papelera.
    public function restore(User $user, Graduate $graduate): bool { return $user->can('gestionar_papelera'); }
    public function restoreAny(User $user): bool { return $user->can('gestionar_papelera'); }
    public function forceDelete(User $user, Graduate $graduate): bool { return $user->can('gestionar_papelera'); }
    public function forceDeleteAny(User $user): bool { return $user->can('gestionar_papelera'); }

}
