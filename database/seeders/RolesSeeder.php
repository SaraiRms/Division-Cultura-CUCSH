<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

// Realizado según la matriz de permisos del cliente en roles (permisos de Spatie).
// Cada fila de la matriz es un permiso; cada columna es un rol.

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::where('name', 'admin_tecnico')->update(['name' => 'administrador']);

        // Las filas de la matriz.
        $permisos = [
            'crear_borradores',
            'editar_contenido_propio',
            'editar_contenido_ajeno',
            'revisar_contenido',              // revisar y devolver
            'aprobar_contenido',              // aprobar contenido oficial
            'publicar_contenido',
            'eliminar_contenido_publicado',   //Aplica con cualquier contenido
            'gestionar_directorio',           // maestros y Rectoría
            'gestionar_estructura',           // departamentos y programas
            'gestionar_usuarios', 
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // Qué permiso tiene cada rol.

        $roles = [
            // El dueño: tiene todo.
            'administrador' => $permisos,

            // El responsable: todo menos usuarios.
            'responsable_institucional' => [
                'crear_borradores', 'editar_contenido_propio', 'editar_contenido_ajeno',
                'revisar_contenido', 'aprobar_contenido', 'publicar_contenido',
                'eliminar_contenido_publicado',
                'gestionar_directorio', 'gestionar_estructura',
            ],

            // El editor: sin usuarios ni departamentos.
            'editor' => [
                'crear_borradores', 'editar_contenido_propio', 'editar_contenido_ajeno',
                'revisar_contenido', 'aprobar_contenido', 'publicar_contenido',
                'eliminar_contenido_publicado',
                'gestionar_directorio',
            ],

            // El auxiliar: solo crea borradores.
            'auxiliar_academico' => ['crear_borradores', 'editar_contenido_propio'],
        ];

        foreach ($roles as $nombre => $permisosDelRol) {
            Role::firstOrCreate(['name' => $nombre, 'guard_name' => 'web'])
                ->syncPermissions($permisosDelRol);
        }
    }
}
