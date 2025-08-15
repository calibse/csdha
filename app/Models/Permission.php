<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Permission extends Pivot
{
	protected $table = 'permissions';

	public function positions(): BelongsToMany
	{
		return $this->belongsToMany(Position::class, 'position_permissions',
			'permission_id', 'position_id');
	}

	public function resourceType(): BelongsTo
	{
		return $this->belongsTo(ResourceType::class);
	}

	public function resourceActionType(): BelongsTo
	{
		return $this->belongsTo(ResourceActionType::class);
	}
}
