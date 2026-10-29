<?php

namespace App\Policies;

use App\Models\User;

// Solo quien tenga el permiso "gestionar_usuarios" (el Administrador técnico) ve y maneja usuarios y roles.
class UserPolicy
{
    private function puede(User $user): bool
    {
        return $user->can('gestionar_usuarios');
    }

    public function viewAny(User $user): bool { return $this->puede($user); }
    public function view(User $user, User $model): bool { return $this->puede($user); }
    public function create(User $user): bool { return $this->puede($user); }
    public function update(User $user, User $model): bool { return $this->puede($user); }
    public function delete(User $user, User $model): bool { return $this->puede($user) && $user->id !== $model->id; }
    public function deleteAny(User $user): bool { return $this->puede($user); }
}
