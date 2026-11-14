<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Contribution extends Model
{
    // author_type: 'alumno' o 'profesor'; se asigna según el botón desde donde se abrió el formulario.
    protected $fillable = [
        'author_type', 'contact_email', 'comentarios', 'pdf_path',
        'title', 'banner_path', 'status', 'reviewed_by', 'reviewed_at',
    ];

    // El correo de contacto no debe mostrarse en la página pública.
    protected $hidden = ['contact_email'];

    public const STATUS_LABELS = [
        'received'  => 'Recibida',
        'in_review' => 'En revisión',
        'published' => 'Publicada',
        'rejected'  => 'Rechazada',
    ];

    public const TYPE_LABELS = [
        'alumno'   => 'Estudiante',
        'profesor' => 'Académico',
    ];

    protected static function booted(): void
    {
        // Al cambiar el estado desde el panel, se anota quién lo revisó y cuándo
        static::saving(function (Contribution $c) {
            if ($c->isDirty('status') && $c->status !== 'received' && auth()->check()) {
                $c->reviewed_by = auth()->id();
                $c->reviewed_at = now();
            }
        });

        // Si se elimina una aportación, también se borra su PDF de la carpeta privada
        static::deleting(function (Contribution $c) {
            Storage::disk('local')->delete($c->pdf_path);
        });
    }

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('author_type', $type);
    }
}
