<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'title', 'slug', 'summary', 'body', 'image_path', 'is_featured',
        'status', 'published_at', 'author_id', 'approved_by', 'approved_at',
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
}
