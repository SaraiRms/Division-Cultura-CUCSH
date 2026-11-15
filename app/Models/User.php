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

    protected static function booted(): void
    {
        static::deleting(function (User $usuario) {
            // No se elimina al último administrador.
            if ($usuario->hasRole('administrador') && User::role('administrador')->count() <= 1) {
                return false;
            }
        });
    }

}
