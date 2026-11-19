<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use SoftDeletes;

    public const STATUS_LABELS = [
        'draft'     => 'Borrador',
        'pending'   => 'Pendiente',
        'published' => 'Publicado',
        'rejected'  => 'Rechazado',
    ];

    protected $table = 'news';

    protected $fillable = [
        'title', 'slug', 'summary', 'body', 'image_path', 'is_featured',
        'status', 'published_at', 'author_id', 'approved_by', 'approved_at',
        'review_note', 'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Si no se escribe slug, se genera del título y se hace único.
        static::creating(function (News $news) {
            if (blank($news->slug)) {
                $base = Str::slug($news->title);
                $slug = $base;
                $i = 2;
                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $news->slug = $slug;
            }
        });

        // Al publicar (desde el formulario o la tabla) se guarda la fecha y quién la aprobó.
        static::saving(function (News $news) {
            if ($news->isDirty('status') && $news->status === 'published') {
                $news->published_at ??= now();
                $news->approved_by ??= auth()->id();
                $news->approved_at ??= now();
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Hasta 4 destacadas para el carrusel del inicio (el máximo de 4 se valida en el formulario de Filament).
    public function scopeHero($query)
    {
        return $query->published()->where('is_featured', true)->orderByDesc('published_at')->limit(4);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Busca en título, resumen y cuerpo (el % y _ del texto se escapan para que no actúen como comodines).
    public function scopeSearch($query, ?string $texto)
    {
        $texto = trim((string) $texto);

        if ($texto === '') {
            return $query;
        }

        $t = '%' . addcslashes($texto, '%_\\') . '%';

        return $query->where(fn ($w) => $w
            ->where('title', 'like', $t)
            ->orWhere('summary', 'like', $t)
            ->orWhere('body', 'like', $t));
    }

}
