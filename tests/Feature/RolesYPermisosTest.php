<?php

namespace Tests\Feature;

use App\Models\AcademicProgram;
use App\Models\Department;
use App\Models\DirectoryAdmin;
use App\Models\News;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesYPermisosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesSeeder::class);
    }

    private function usuario(string $rol): User
    {
        $usuario = User::factory()->create(['is_active' => true]);
        $usuario->assignRole($rol);

        return $usuario;
    }

    private function noticia(?User $autor = null, string $estado = 'draft'): News
    {
        return News::create([
            'title'     => 'Prueba ' . uniqid(),
            'status'    => $estado,
            'author_id' => $autor?->id,
        ]);
    }

    public function test_solo_el_administrador_gestiona_usuarios(): void
    {
        $this->assertTrue($this->usuario('administrador')->can('create', User::class));

        foreach (['responsable_institucional', 'editor', 'auxiliar_academico'] as $rol) {
            $this->assertFalse($this->usuario($rol)->can('create', User::class), $rol);
        }
    }

    public function test_departamentos_son_del_administrador_y_el_responsable(): void
    {
        foreach (['administrador', 'responsable_institucional'] as $rol) {
            $this->assertTrue($this->usuario($rol)->can('create', Department::class), $rol);
        }

        foreach (['editor', 'auxiliar_academico'] as $rol) {
            $this->assertFalse($this->usuario($rol)->can('create', Department::class), $rol);
        }
    }

    public function test_el_directorio_lo_manejan_todos_menos_el_auxiliar(): void
    {
        foreach (['administrador', 'responsable_institucional', 'editor'] as $rol) {
            $this->assertTrue($this->usuario($rol)->can('create', DirectoryAdmin::class), $rol);
        }

        $this->assertFalse($this->usuario('auxiliar_academico')->can('create', DirectoryAdmin::class));
    }

    public function test_quien_puede_eliminar_contenido(): void
    {
        $noticia = $this->noticia(null, 'published');

        foreach (['administrador', 'responsable_institucional', 'editor'] as $rol) {
            $this->assertTrue($this->usuario($rol)->can('delete', $noticia), $rol);
        }

        $this->assertFalse($this->usuario('auxiliar_academico')->can('delete', $noticia));
    }

    public function test_restaurar_y_eliminar_definitivamente_es_solo_del_administrador(): void
    {
        $noticia = $this->noticia();
        $admin = $this->usuario('administrador');

        $this->assertTrue($admin->can('restore', $noticia));
        $this->assertTrue($admin->can('forceDelete', $noticia));

        foreach (['responsable_institucional', 'editor', 'auxiliar_academico'] as $rol) {
            $usuario = $this->usuario($rol);
            $this->assertFalse($usuario->can('restore', $noticia), $rol);
            $this->assertFalse($usuario->can('forceDelete', $noticia), $rol);
        }
    }

    public function test_el_auxiliar_edita_lo_suyo_solo_si_es_borrador_o_devuelto(): void
    {
        $auxiliar = $this->usuario('auxiliar_academico');

        $this->assertTrue($auxiliar->can('update', $this->noticia($auxiliar, 'draft')));
        $this->assertTrue($auxiliar->can('update', $this->noticia($auxiliar, 'rejected')));
        $this->assertFalse($auxiliar->can('update', $this->noticia($auxiliar, 'pending')));
        $this->assertFalse($auxiliar->can('update', $this->noticia($auxiliar, 'published')));
        $this->assertFalse($auxiliar->can('update', $this->noticia($this->usuario('editor'), 'draft')));
    }

    public function test_un_usuario_desactivado_no_entra_al_panel(): void
    {
        $panel = Filament::getPanel('admin');

        $activo = $this->usuario('editor');
        $inactivo = $this->usuario('editor');
        $inactivo->update(['is_active' => false]);

        $this->assertTrue($activo->canAccessPanel($panel));
        $this->assertFalse($inactivo->canAccessPanel($panel));
    }

    public function test_no_se_elimina_al_ultimo_administrador(): void
    {
        $admin = $this->usuario('administrador');

        $this->assertFalse($admin->delete());
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_no_se_elimina_un_departamento_con_programas(): void
    {
        $departamento = Department::create(['name' => 'Departamento de prueba']);
        AcademicProgram::create([
            'department_id' => $departamento->id,
            'name'          => 'Licenciatura de prueba',
            'type'          => 'licenciatura',
        ]);

        $this->assertFalse($departamento->delete());
        $this->assertDatabaseHas('departments', ['id' => $departamento->id]);
    }
}