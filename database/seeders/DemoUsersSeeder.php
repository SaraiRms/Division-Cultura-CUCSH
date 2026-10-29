<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// Nombre de usuario y contraseña de los usuarios de demostración.

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Administrador técnico (demo)', 'admin@example.test', 'admin_tecnico'],
            ['Responsable institucional (demo)', 'responsable@example.test', 'responsable_institucional'],
            ['Editor (demo)', 'editor@example.test', 'editor'],
            ['Auxiliar académico (demo)', 'auxiliar@example.test', 'auxiliar_academico'],
        ];

        foreach ($users as [$name, $email, $role]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => 'password', 'is_active' => true],
            );
            $user->syncRoles([$role]);
        }
    }
}
