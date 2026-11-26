<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectoryRectoria extends Model
{
    use SoftDeletes;

    protected $table = 'directory_rectoria';

    public const NIVELES = [
        1 => 'Rector(a)',
        2 => 'Secretario(a) académico(a)',
        3 => 'Secretario(a) administrativo(a)',
        4 => 'Director(a) de división',
        5 => 'Coordinador(a)',
        6 => 'Jefe(a) de unidad',
        7 => 'Personal de apoyo',
    ];
    
    protected $fillable = ['full_name', 'cargo', 'jerarquia', 'area', 'photo_path', 'created_by'];
    

    protected static function booted(): void
    {
        static::saving(function (DirectoryRectoria $persona) {
            $persona->cargo = self::NIVELES[$persona->jerarquia] ?? $persona->cargo;
        });
    }
    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
