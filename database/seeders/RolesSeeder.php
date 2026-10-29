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

        // Las filas de la matriz.
        $permisos = [
            'crear_borradores',
            'editar_contenido_propio',
            'editar_contenido_ajeno',
            'revisar_contenido',            // revisar y devolver
            'aprobar_contenido',            // aprobar contenido oficial
            'publicar_contenido',
            'gestionar_usuarios',           // usuarios y roles
            'eliminar_contenido_publicado',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // Qué permiso tiene cada rol.

        $roles = [
            // Administrador técnico: gestiona usuarios y roles. Aprobar = "no por defecto"; publicar = "según política";
            // eliminar publicado = "restringido".
            'admin_tecnico' => [
                'crear_borradores', 'editar_contenido_propio', 'editar_contenido_ajeno',
                'revisar_contenido', 'gestionar_usuarios',
            ],

            // Responsable institucional (jefe de división): aprueba contenido oficial.
            // SUPUESTO: publicar ("según política") lo dejé activo. Editar ajeno y eliminar publicado
            // ("según autorización") quedan apagados.
            'responsable_institucional' => [
                'crear_borradores', 'editar_contenido_propio',
                'revisar_contenido', 'aprobar_contenido', 'publicar_contenido',
            ],

            // Editor (secretaría): publica. Aprobar es "según delegación" (apagado).
            // SUPUESTO: eliminar publicado ("según política") lo dejé activo.
            'editor' => [
                'crear_borradores', 'editar_contenido_propio', 'editar_contenido_ajeno',
                'revisar_contenido', 'publicar_contenido', 'eliminar_contenido_publicado',
            ],

            // Auxiliar académico (servicio social / externos): solo crea borradores.
            // Lo que crea pasa a revisión y ya no lo puede editar (por eso las X naranjas).
            'auxiliar_academico' => [
                'crear_borradores',
            ],
        ];

        foreach ($roles as $nombre => $permisosDelRol) {
            Role::firstOrCreate(['name' => $nombre, 'guard_name' => 'web'])
                ->syncPermissions($permisosDelRol);
        }
    }
}
