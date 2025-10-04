<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleAccount extends Model
{
    protected $table = 'user_google_accounts';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
