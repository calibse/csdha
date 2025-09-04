<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Position extends Model
{	
    protected static function booted(): void
    {
        static::saving(function (Position $position) {
            if ($position->position_order === null) {
                $lastOrder = Position::orderBy('position_order', 'desc')
                    ->value('position_order');
                $position->position_order = $lastOrder === 255 ? $lastOrder 
                    : $lastOrder + 1;
            }
        });
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
    
    public function permissions(): BelongsToMany 
    {
        return $this->belongsToMany(Permission::class, 'position_permissions',
            'position_id', 'permission_id');
    }

    public function signupInvitations(): HasMany
    {
        return $this->hasMany(SignupInvitation::class);
    }

    #[Scope]
    protected function ofName(Builder $query, string $name): void
    {
        $name = strtolower($name);
        $query->whereRaw('lower(name) = ?', $name);
    }

    #[Scope]
    protected function open(Builder $query): void
    {
        $query->doesntHave('user')->where(function ($query) {
            $query->whereRelation('signupInvitations', 'is_accepted', 0)
                ->orDoesntHave('signupInvitations');
        });

    }
}
