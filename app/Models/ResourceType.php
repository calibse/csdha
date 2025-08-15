<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceType extends Model
{
    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(ResourceActionType::class, 
            'permissions')
            ->as('permission')
            ->withPivot('id')
            ->withTimestamps()
            ->using(Permission::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
