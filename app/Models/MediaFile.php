<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// Tabla "files". Se llama MediaFile para no chocar con la facade File de Laravel.
class MediaFile extends Model
{
    use SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'title', 'file_type', 'in_gallery', 'file_path', 'mime_type', 'file_size',
        'status', 'uploaded_by', 'approved_by', 'approved_at',
    ];

    protected function casts(): array
    {
        return ['in_gallery' => 'boolean', 'approved_at' => 'datetime'];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Imágenes que salen en la sección Galería.
    public function scopeGallery($query)
    {
        return $query->published()->where('in_gallery', true)->where('file_type', 'image');
    }
}
