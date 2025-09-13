<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Traits\HasPublicId;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasPublicId, SoftDeletes;

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function eventDates(): BelongsToMany
    {
        return $this->belongsToMany(EventDate::class, 'event_officer_attendees')
            ->withPivot('created_at')->withTimestamps();
    }

	public function hasPerm($permissions)
	{
        if (!$this->position) return false;
        if (is_array($permissions) && count($permissions) === 0) {
            return false;
        }
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }
        $query = $this->position->permissions()->where(function ($query)
                use ($permissions) {
            for ($i = 0; $i < count($permissions); $i++) {
                [$resource, $action] = explode('.', $permissions[$i]);
                $resourceQuery = function ($query) use ($resource) {
                    $query->where('name', $resource);
                };
                $actionQuery = function ($query) use ($action) {
                    $query->where('name', $action);
                };
                $permQuery = function ($query) use ($resourceQuery, $actionQuery) {
                    $query->whereHas('resourceType', $resourceQuery)
                        ->whereHas('resourceActionType', $actionQuery);
                };
                if ($i === 0) {
                    $query->where($permQuery);
                } else {
                    $query->orWhere($permQuery);
                }
            }
        });
        return $query->exists();

        /*
        if (is_string($permissions))
            $permissions = [$permissions];
        foreach ($permissions as $perm) {
            if (!$this->position) return false;;
            $perm = explode('.', $perm);
            $resource = ResourceType::firstWhere('name', $perm[0]);
            $action = ResourceActionType::firstWhere('name', $perm[1]);
            if (!$resource || !$action) return false;
            if (!($this->position->permissions()->whereBelongsTo($resource)->whereBelongsTo($action)
                    ->first()))
                return false;
        }
        return true;
        */
	}

    public function hasPosition($positions)
    {
        if (!$this->position) return false;
        if (!is_array($positions))
            $positions = [$positions];
		foreach ($positions as $position) {
			if (strtolower($this->position->name) == strtolower($position))
				return true;
		}
		return false;
	}

    public function fullName(): Attribute
    {
		$fullName = preg_replace('!\s+!', ' ', "$this->first_name
            $this->middle_name $this->last_name $this->suffix_name");
        return Attribute::make(
			get: fn () => $fullName,
        );
	}

    public function isOfficer(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->position_id ? true : false
        );
    }

    public function positionName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->position ?
                strtolower($this->position->name) : null
        );
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public function platform(): HasMany
    {
        return $this->hasMany(Platform::class);
    }

    public function partnership(): HasMany
    {
        return $this->hasMany(Partnership::class);
    }

    public function activityLog(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function announcement(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function eventDeliverables(): BelongsToMany
    {
        return $this->belongsToMany(EventDeliverable::class,
            'events_deliverable_assignee');
    }

    public function hasEventDeliverables(Event $event)
    {
        foreach ($event->deliverables as $deliverable) {
            if ($deliverable->assignees->contains($this))
                return true;
        }
        return false;
    }

    public function editableEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_editor');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin()
    {
        if ($this->role?->name === 'admin') return true;
        return false;
    }

    public function gspoas(): BelongsToMany
    {
        return $this->belongsToMany(GSPOA::class, 'gspoa_editors');
    }

    #[Scope]
    protected function notOfPosition(Builder $query, $positions): void
    {
        if (is_string($positions)) {
            $positions = [$positions];
        }
        $allQuery = $query;
        foreach ($positions as $posName) {
            $position = Position::whereRaw('lower(name) = ?', $posName)->first();
            if ($position) {
                $allQuery = $allQuery->where('position_id', '!=', $position->id);
            }
        }
        $allQuery;
    }

    #[Scope]
    protected function president(Builder $query): void
    {
        $query->whereHas('position', function ($query) {
            $query->whereRaw('lower(name)', 'president');
        });
    }

    #[Scope]
    protected function adviser(Builder $query): void
    {
        $query->whereHas('position', function ($query) {
            $query->whereRaw('lower(name)', 'adviser');
        });
    }

    #[Scope]
    protected function ofPosition(Builder $query, $positions): void
    {
        if (is_string($positions)) {
            $positions = [$positions];
        }
        $query->whereHas('position', function ($query) use ($positions) {
            $query->whereIn(DB::raw('lower(name)'), $positions);
        });
    }

    #[Scope]
    protected function notAuthUser(Builder $query): void
    {
        $query->whereKeyNot(auth()->user()->id);
    }

    #[Scope]
    protected function withPerm(Builder $query, string $permission): void
    {
        [$resource, $action] = explode('.', $permission);
        $query->whereHas('position', function ($query)
                use ($resource, $action) {
            $query->whereHas('permissions', function ($query)
                    use ($resource, $action) {
                $query->whereHas('resourceType', function ($query)
                        use ($resource, $action) {
                    $query->where('name', $resource);
                })->whereHas('resourceActionType', function ($query)
                        use ($resource, $action) {
                    $query->where('name', $action);
                });
            });
        });
    }

    #[Scope]
    protected function accomReportEditor(Builder $query): void
    {
        $query->withPerm('accomplishment-reports.edit')
            ->notOfPosition('adviser');
    }

    protected function entryTime(): Attribute
    {
        $time = $this->pivot?->created_at;
        return Attribute::make(
            get: fn () => $time
        );
    }
}
