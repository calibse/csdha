<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GSPOAEventParticipant extends Model
{
    public function gspoaEvent(): BelongsTo
    {
        return $this->belongsTo(GSPOA::class);
    }

    public function studentYear(): BelongsToMany
    {
        return $this->belongsTo(StudentYear::class);
    }
}
