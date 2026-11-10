<?php

namespace App\Models;

use App\Models\Concerns\HasYoutubeThumbnail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Podcast extends Model
{
    use SoftDeletes, HasYoutubeThumbnail;

     public const STATUS_LABELS = [
        'draft' => 'Borrador',
        'published' => 'Publicado',
    ];

    protected $fillable = ['title', 'description', 'image_path', 'video_url', 'status', 'published_at', 'created_by'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::saving(function (Podcast $podcast) {
            if ($podcast->status === 'published' && blank($podcast->published_at)) {
                $podcast->published_at = now();

            }
            
        });
    }

    protected function youtubeUrlField(): string
    {
        return 'video_url';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
