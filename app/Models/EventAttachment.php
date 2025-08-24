<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttachment extends Model
{
    public function set(): BelongsTo
    {
        return $this->belongsTo(EventAttachmentSet::class, 'event_attachment_set_id');
    }
}
