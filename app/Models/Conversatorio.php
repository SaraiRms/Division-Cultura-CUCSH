<?php

namespace App\Models;

use App\Models\Concerns\HasYoutubeThumbnail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversatorio extends Model
{
    use SoftDeletes, HasYoutubeThumbnail;

    protected $table = 'conversatorios';

    protected $fillable = ['title', 'speaker', 'description', 'scheduled_at', 'stream_url', 'status', 'created_by'];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime'];
    }

    protected function youtubeUrlField(): string
    {
        return 'stream_url';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // El conversatorio grande: el que está en vivo; si no hay, el próximo programado.
    public static function featured(): ?self
    {
        return static::where('status', 'live')->latest('scheduled_at')->first()
            ?? static::where('status', 'scheduled')->where('scheduled_at', '>=', now())->orderBy('scheduled_at')->first();
    }

    // Los últimos terminados (por defecto 3) para "Conversatorios anteriores".
    public function scopeLatestFinished($query, int $limit = 3)
    {
        return $query->where('status', 'finished')->orderByDesc('scheduled_at')->limit($limit);
    }
}
