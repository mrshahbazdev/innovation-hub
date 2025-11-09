<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdeaComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'idea_id',
        'user_id',
        'body',
    ];

    // Har comment ek user se wabasta hai
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Har comment ek idea se wabasta hai
    public function idea(): BelGongsTo
    {
        return $this->belongsTo(Idea::class);
    }
}
