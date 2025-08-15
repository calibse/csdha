<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventDeliverableTask extends Model
{
    public function eventDeliverable(): BelongsTo
    {
        return $this->belongsTo(EventDeliverable::class);
    }    
}
