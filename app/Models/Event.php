<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'location', 'image_path', 'starts_at', 'status', 'created_by'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime'];
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
}
