<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Idea extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'team_id',
        'submitter_type',
        'contact_info',
        'problem_short',
        'goal',
        'problem_detail',
        'status',
        'schmerz',
        'prio_1',
        'prio_2',
        'umsetzung',
        'loesung',
        'kosten',
        'dauer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status',
                'schmerz',
                'loesung',
                'kosten',
                'dauer',
                'prio_1',
                'prio_2',
                'umsetzung',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "This idea was {$eventName}");
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(IdeaComment::class)->orderBy('created_at', 'desc');
    }
}
