<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdministradorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesSeeder::class);
    }

    public function test_crea_un_administrador_del_cliente(): void
    {
        $this->artisan('admin:crear', ['--nombre' => 'Jefa Prueba', '--email' => 'jefa@example.test'])
            ->expectsQuestion('Contraseña (mínimo 10 caracteres)', 'una-clave-larga-1')
            ->expectsQuestion('Repite la contraseña', 'una-clave-larga-1')
            ->assertSuccessful();

        $usuario = User::where('email', 'jefa@example.test')->firstOrFail();

        $this->assertTrue($usuario->hasRole('administrador'));
        $this->assertFalse($usuario->hasRole('administrador_tecnico'));
        $this->assertTrue($usuario->is_active);
        $this->assertTrue(Hash::check('una-clave-larga-1', $usuario->password));
    }

    public function test_crea_un_administrador_tecnico(): void
    {
        $this->artisan('admin:crear', ['--tecnico' => true, '--nombre' => 'Ing. Prueba', '--email' => 'ing@example.test'])
            ->expectsQuestion('Contraseña (mínimo 10 caracteres)', 'una-clave-larga-1')
            ->expectsQuestion('Repite la contraseña', 'una-clave-larga-1')
            ->assertSuccessful();

        $this->assertTrue(User::where('email', 'ing@example.test')->firstOrFail()->hasRole('administrador_tecnico'));
    }

    public function test_rechaza_contrasenas_cortas_o_que_no_coinciden(): void
    {
        $this->artisan('admin:crear', ['--nombre' => 'X', '--email' => 'x@example.test'])
            ->expectsQuestion('Contraseña (mínimo 10 caracteres)', 'corta')
            ->expectsQuestion('Repite la contraseña', 'corta')
            ->assertFailed();

        $this->assertDatabaseMissing('users', ['email' => 'x@example.test']);
    }
}