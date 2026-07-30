<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'note'])]
class Quest extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(QuestCompletion::class);
    }

    public function scopeWithTodayCompletion($query)
    {
        return $query->withExists([
            'completions' => fn ($q) => $q->where('completed_on', '=', today())
        ]);
    }

    public function isCompletedToday()
    {
        return $this->completions()->where('completed_on', '=', today())->exists();
    }
}
