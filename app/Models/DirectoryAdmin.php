<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// Directorio de maestros y personal de la División.
class DirectoryAdmin extends Model
{
    use SoftDeletes;

    protected $table = 'directory_admin';

    public const NIVELES = [
        1 => 'Director(a)',
        2 => 'Jefe(a) de departamento',
        3 => 'Secretario(a) académico(a)',
        4 => 'Coordinador(a)',
        5 => 'Asistente académico(a)',
        6 => 'Personal académico',
    ];
    protected $fillable = [
        'full_name', 'cargo', 'jerarquia', 'email', 'phone', 'phone_ext', 'nivel_academico',
        'materias', 'area_conocimiento', 'cv_summary', 'cv_path',
        'photo_path', 'created_by',
    ];

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_directory_admin', 'directory_admin_id', 'department_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted(): void
    {
        // El cargo no se escribe a mano: sale del nivel elegido.
        static::saving(function (DirectoryAdmin $persona) {
            $persona->cargo = self::NIVELES[$persona->jerarquia] ?? $persona->cargo;
        });
    }
}
