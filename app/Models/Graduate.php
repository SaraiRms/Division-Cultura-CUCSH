<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Graduate extends Model
{
    use SoftDeletes;

    public const STATUS_LABELS = [
        'pending' => 'Pendiente de autorizar',
        'published' => 'Publicado',
        'rejected' => 'Rechazado',
    ];

    protected $fillable = ['program_id', 'title', 'image_path', 'status', 'uploaded_by', 'approved_by', 'approved_at'];

    protected function casts(): array
    {
        return ['approved_at' => 'datetime'];
    }

    protected static function booted(): void
{
    // Al publicarse se guarda quién lo aprobó y cuándo.
    static::saving(function (Graduate $graduate) {
        if ($graduate->isDirty('status') && $graduate->status === 'published') {
            $graduate->approved_by ??= auth()->id();
            $graduate->approved_at ??= now();
        }
    });
}

    public function program(): BelongsTo
    {
        return $this->belongsTo(AcademicProgram::class, 'program_id');
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
}
