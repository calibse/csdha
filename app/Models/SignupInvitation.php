<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignupInvitation extends Model
{
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
