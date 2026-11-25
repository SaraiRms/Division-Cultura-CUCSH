<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CrearAdministrador extends Command
{
    protected $signature = 'admin:crear
                            {--tecnico : Crea un administrador técnico (soporte) en lugar del administrador del cliente}
                            {--nombre= : Nombre completo}
                            {--email= : Correo con el que iniciará sesión}';

    protected $description = 'Crea una cuenta de administrador pidiendo la contraseña de forma segura';

    public function handle(): int
    {
        $rol = $this->option('tecnico') ? 'administrador_tecnico' : 'administrador';

        if (! Role::where('name', $rol)->exists()) {
            $this->error('Los roles todavía no existen. Corre primero: php artisan migrate --seed');

            return self::FAILURE;
        }

        $nombre = $this->option('nombre') ?: $this->ask('Nombre completo');
        $email = $this->option('email') ?: $this->ask('Correo electrónico');
        $password = $this->secret('Contraseña (mínimo 10 caracteres)');
        $confirmacion = $this->secret('Repite la contraseña');

        $validador = Validator::make(
            [
                'nombre'                => $nombre,
                'email'                 => $email,
                'password'              => $password,
                'password_confirmation' => $confirmacion,
            ],
            [
                'nombre'   => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:10', 'confirmed'],
            ],
            [
                'nombre.required'    => 'El nombre es obligatorio.',
                'email.required'     => 'El correo es obligatorio.',
                'email.email'        => 'El correo no tiene un formato válido.',
                'password.required'  => 'La contraseña es obligatoria.',
                'password.min'       => 'La contraseña debe tener al menos 10 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
            ],
        );

        if ($validador->fails()) {
            foreach ($validador->errors()->all() as $mensaje) {
                $this->error($mensaje);
            }

            return self::FAILURE;
        }

        // Si el correo ya existe, se pide confirmación antes de cambiar nada
        if (User::where('email', $email)->exists()
            && ! $this->confirm("Ya existe una cuenta con {$email}. ¿Actualizar su nombre, contraseña y rol?")) {
            $this->warn('No se hizo ningún cambio.');

            return self::FAILURE;
        }

        $usuario = User::updateOrCreate(
            ['email' => $email],
            ['name' => $nombre, 'password' => $password, 'is_active' => true],  // el modelo encripta la contraseña
        );

        $usuario->syncRoles([$rol]);

        $this->info("Listo: {$email} ahora es {$rol}.");

        return self::SUCCESS;
    }
}