<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendee extends Model
{
    public function student(): BelongsTo
    {
        return $this->belongsTo(EventStudent::class, 'event_student_id');
    }

    public function eventDate(): BelongsTo
    {
        return $this->belongsTo(EventDate::class);
    }
}
