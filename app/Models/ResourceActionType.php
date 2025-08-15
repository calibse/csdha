<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceActionType extends Model
{
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(ResourceType::class, 
            'permissions');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
