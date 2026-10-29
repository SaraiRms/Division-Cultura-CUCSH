<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicProgram extends Model
{
    const CREATED_AT = null;

    protected $fillable = ['department_id', 'name', 'type', 'description', 'sort_order', 'updated_by'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function graduates(): HasMany
    {
        return $this->hasMany(Graduate::class, 'program_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
