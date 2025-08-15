<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventEvalQuestion extends Model
{
    protected $table = 'event_eval_form_questions';

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
