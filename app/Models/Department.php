<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    // Solo existe updated_at (no created_at), igual que en el diagrama.
    const CREATED_AT = null;

    protected $fillable = ['name', 'description', 'image_path', 'show_card', 'updated_by'];

    protected function casts(): array
    {
        return ['show_card' => 'boolean'];
    }

    public function programs(): HasMany
    {
        return $this->hasMany(AcademicProgram::class)->orderBy('sort_order');
    }

    public function directoryMembers(): BelongsToMany
    {
        return $this->belongsToMany(DirectoryAdmin::class, 'department_directory_admin', 'department_id', 'directory_admin_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Las 4 tarjetas de la página (la División no genera tarjeta).
    public function scopeCards($query)
    {
        return $query->where('show_card', true);
    }
}
