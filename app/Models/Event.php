<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    public const STATUS_LABELS = [
        'draft' => 'Borrador',
        'published' => 'Publicado',
        'cancelled' => 'Cancelado',
    ];

    protected $fillable = [
        'title', 'description', 'location', 'image_path', 'starts_at', 
        'status', 'is_featured', 'created_by'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'is_featured' => 'boolean'];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Eventos de un mes (para el calendario).
    public function scopeInMonth($query, int $year, int $month)
    {
        return $query->whereYear('starts_at', $year)->whereMonth('starts_at', $month);
    }

    // Eventos publicados de hoy en adelante, del más cercano al más lejano.
    public function scopeUpcoming($query)
    {
        return $query->published()
            ->where('starts_at', '>=', now()->startOfDay())
            ->orderBy('starts_at');
    }

    // Eventos destacados para el banner del inicio (máximo 2, solo próximos y con imagen).
    public function scopeHero($query)
    {
        return $query->published()
            ->where('is_featured', true)
            ->where('starts_at', '>=', now())
            ->whereNotNull('image_path')
            ->orderBy('starts_at')
            ->limit(3);
    }

}
