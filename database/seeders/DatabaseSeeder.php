<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            DepartmentsSeeder::class,
        ]);

        // Usuarios de prueba solo en local.
        if (app()->environment('local')) {
            $this->call(DemoUsersSeeder::class);
        }
    }
}
