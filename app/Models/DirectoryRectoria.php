<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectoryRectoria extends Model
{
    use SoftDeletes;

    protected $table = 'directory_rectoria';

    protected $fillable = ['full_name', 'cargo', 'area', 'photo_path', 'created_by'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
