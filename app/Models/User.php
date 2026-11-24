<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

// Reemplaza el User.php que trae Laravel. Requiere filament/filament y spatie/laravel-permission instalados.
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Solo usuarios activos entran al panel administrativo.
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }

    public function esTecnico(): bool
    {
        return $this->hasRole('administrador_tecnico');
    }

    // Roles que esta persona puede asignar. null = todos (el técnico).
    public function rolesQueAsigna(): ?array
    {
        if ($this->esTecnico()) {
            return null;
        }

        return $this->hasRole('administrador') ? ['auxiliar_academico'] : [];
    }

    // Solo se gestiona a quien tiene únicamente roles que uno puede asignar.
    public function puedeGestionarA(User $objetivo): bool
    {
        $permitidos = $this->rolesQueAsigna();

        if ($permitidos === null) {
            return true;
        }

        $roles = $objetivo->getRoleNames();

        return $roles->isNotEmpty() && $roles->diff($permitidos)->isEmpty();
    }

    // Siempre debe quedar al menos una cuenta de cada tipo de administrador.
    public function esUltimoAdministrador(): bool
    {
        foreach (['administrador', 'administrador_tecnico'] as $rol) {
            if ($this->hasRole($rol) && static::role($rol)->count() <= 1) {
                return true;
            }
        }

        return false;
    }

    // Se revisa ANTES de eliminar, porque al eliminar Spatie primero le quita los roles al usuario.
    public function delete()
    {
        if ($this->esUltimoAdministrador()) {
            return false;
        }

        return parent::delete();
    }

}
