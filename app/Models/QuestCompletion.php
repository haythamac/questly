<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['quest_id', 'user_id', 'completed_on', 'completed_at'])]
class QuestCompletion extends Model
{
    public function casts() : array
    {
        return [
            'completed_on' => 'date',
            'completed_at' => 'date'
        ];
    }
    
    public function quest(): BelongsTo
    {
        return $this->belongsTo(Quest::class);
    }
}
