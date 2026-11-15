<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

// Base para secciones que se manejan con un solo permiso.
abstract class PermisoPolicy
{
    abstract protected function permiso(): string;

    protected function puede(User $user): bool
    {
        return $user->can($this->permiso());
    }

    public function viewAny(User $user): bool { return $this->puede($user); }
    public function view(User $user, Model $model): bool { return $this->puede($user); }
    public function create(User $user): bool { return $this->puede($user); }
    public function update(User $user, Model $model): bool { return $this->puede($user); }
    public function delete(User $user, Model $model): bool { return $this->puede($user); }
    public function deleteAny(User $user): bool { return $this->puede($user); }

    // Restaurar y eliminar definitivamente: solo el administrador (lo resuelve Gate::before).
    public function restore(User $user, Model $model): bool { return false; }
    public function restoreAny(User $user): bool { return false; }
    public function forceDelete(User $user, Model $model): bool { return false; }
    public function forceDeleteAny(User $user): bool { return false; }
}