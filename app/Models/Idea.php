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

    protected static function boot()
    {
        parent::boot();

        // Yeh 'saving' event har create() aur update() se pehle chalega
        static::saving(function ($idea) {

            // 1. Errors se bachne ke liye values ko number bana lein
            $kosten = is_numeric($idea->kosten) ? $idea->kosten : 0;
            $dauer = is_numeric($idea->dauer) ? $idea->dauer : 0;
            $schmerz = is_numeric($idea->schmerz) ? $idea->schmerz : 0;

            // 2. FORMULA 1: Prio 1 = (Kosten / 100) + Dauer
            $prio1 = ($kosten / 100) + $dauer;

            // 3. FORMULA 2: Prio 2 = Prio 1 / Schmerz
            // Division by zero se bachne ke liye check karein
            $prio2 = ($schmerz > 0) ? ($prio1 / $schmerz) : 0;

            // 4. Model par nayi (calculated) values set karein
            $idea->prio_1 = $prio1;
            $idea->prio_2 = $prio2;
        });
    }
}