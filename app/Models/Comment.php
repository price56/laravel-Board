<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperComment
 */
class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }
}
