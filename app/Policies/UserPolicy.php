<?php

namespace App\Policies;

use App\Models\User;

// Quién puede gestionar cuentas de usuario. El técnico pasa por Gate::before.
// El administrador solo crea y edita auxiliares; las cuentas técnicas no las ve nadie más.
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('gestionar_usuarios');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('gestionar_usuarios') && ! $model->esTecnico();
    }

    public function create(User $user): bool
    {
        return $user->can('gestionar_usuarios');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('gestionar_usuarios') && $user->puedeGestionarA($model);
    }

    public function delete(User $user, User $model): bool
    {
        return $this->update($user, $model) && $user->id !== $model->id;
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('gestionar_usuarios');
    }

    // Desactivar o reactivar una cuenta: cualquiera que no sea técnica ni la propia.
    public function toggleActive(User $user, User $model): bool
    {
        return $user->can('gestionar_usuarios') && ! $model->esTecnico() && $user->id !== $model->id;
    }
}