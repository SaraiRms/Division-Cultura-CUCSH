<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contribution extends Model
{
    // author_type: 'alumno' o 'profesor'; se asigna según el botón desde donde se abrió el formulario.
    protected $fillable = [
        'author_type', 'contact_email', 'comentarios', 'pdf_path',
        'title', 'banner_path', 'status', 'reviewed_by', 'reviewed_at',
    ];

    // El correo de contacto no debe mostrarse en la página pública.
    protected $hidden = ['contact_email'];

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
