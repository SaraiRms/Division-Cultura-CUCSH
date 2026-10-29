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

    protected $fillable = [
        'full_name', 'cargo', 'email', 'phone', 'cargo_detalle', 'nivel_academico',
        'grado_academico', 'materias', 'area_conocimiento', 'cv_summary', 'cv_path',
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
}
