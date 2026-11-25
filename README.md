# Sitio de la División de Estudios de la Cultura — CUCSH

Sitio web de difusión de la División de Estudios de la Cultura del Centro Universitario de Ciencias Sociales y Humanidades (Universidad de Guadalajara). Tiene dos partes:

- **Sitio público:** noticias, eventos, departamentos y programas, directorio, podcast, conversatorios, egresados y participación académica (alumnos y profesores pueden enviar aportaciones en PDF).
- **Panel administrativo** (`/admin`): gestión de todo el contenido, con flujo de revisión y publicación según el rol de cada persona.

## Tecnología

| Componente | Versión |
|---|---|
| PHP | 8.4 (mínimo 8.2) |
| Laravel | 12 |
| Panel administrativo | Filament 5 |
| Roles y permisos | spatie/laravel-permission |
| Base de datos | MySQL 8 / MariaDB 10.6+ |
| Frontend público | Blade + Tailwind CSS 4 + Alpine.js (Vite) |

## Roles

| Rol | Qué puede hacer |
|---|---|
| Administrador | Todo, incluida la gestión de usuarios y la estructura del sitio |
| Responsable institucional | Revisar, aprobar y publicar contenido |
| Editor | Crear y editar contenido, y publicarlo |
| Auxiliar académico | Crear borradores; su contenido queda pendiente de autorización |

## Instalación para desarrollo local

Requisitos: PHP 8.4, Composer, Node.js 20+ y MySQL.

```bash
git clone https://github.com/SaraiRms/Division-Cultura-CUCSH.git
cd Division-Cultura-CUCSH

composer install
npm install
copy .env.example .env        # en Linux/Mac: cp .env.example .env
php artisan key:generate
```

Edita `.env` con los datos de tu base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) y crea la base vacía en MySQL. Después:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build        # o `npm run dev` mientras se desarrolla
php artisan serve
```

El sitio queda en `http://localhost:8000` y el panel en `http://localhost:8000/admin`.

### Usuarios iniciales

- **En local** (`APP_ENV=local`) el seeder crea usuarios de demostración, uno por rol (contraseña `password`).
- **En producción** no hay usuarios por defecto. Las cuentas se crean con un comando que pide la contraseña en pantalla, así nunca queda guardada en un archivo:

  ```bash
  php artisan admin:crear --tecnico   # administrador técnico (soporte)
  php artisan admin:crear             # administrador del cliente
  ```

  Los demás usuarios (responsable, editor, auxiliares) se crean desde el panel.

## Pruebas

```bash
php artisan test
```

## Despliegue

Los requisitos del servidor, la configuración de `.env` y los pasos de instalación en producción están en **`INSTALACION.md`**.

Puntos clave:

- El servidor debe apuntar a la carpeta `public/`.
- Configurar `APP_URL`, la base de datos, el correo (`MAIL_*`), `CORREO_APORTACIONES` y `CORREO_CONTACTO`.
- Los PDF enviados por el público se guardan en almacenamiento privado y solo se muestran cuando están aprobados.

## Estructura útil

| Ruta | Contenido |
|---|---|
| `app/Filament/Resources` | Recursos del panel (noticias, eventos, usuarios, etc.) |
| `app/Policies` | Permisos por recurso |
| `app/Http/Controllers` | Controladores del sitio público |
| `resources/views` | Vistas públicas (`layouts/public.blade.php` es la plantilla base) |
| `config/site.php` | Redes sociales y datos de contacto del sitio |
| `database/seeders` | Roles, departamentos y administrador inicial |
| `tests` | Pruebas de roles, aportaciones y sitio público |