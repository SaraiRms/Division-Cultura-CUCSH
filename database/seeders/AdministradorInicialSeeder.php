<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// Crea al administrador (dueño) real del sitio.
// Lee sus datos del .env: ADMIN_NOMBRE, ADMIN_EMAIL y ADMIN_PASSWORD.
class AdministradorInicialSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (blank($email) || blank($password)) {
            $this->command?->warn('No se creó el administrador: faltan ADMIN_EMAIL y/o ADMIN_PASSWORD en el .env');
            return;
        }

        $usuario = User::updateOrCreate(
            ['email' => $email],
            [
                'name'      => env('ADMIN_NOMBRE', 'Administrador'),
                'password'  => $password,   // el modelo la encripta solo
                'is_active' => true,
            ],
        );

        $usuario->syncRoles(['administrador']);
    }
}