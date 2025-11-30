<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventLink extends Model
{
    public function event(): BelongsTo
    {
        return $this->belongsTo(EventLink::class);
    }
}
