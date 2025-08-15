<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventDeliverable extends Model
{
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(EventDeliverableTask::class);
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 
            'event_deliverable_assignee');
    }

    public function canBeEditedBy($user)
    {
        if ($this->assignees->contains($user))
            return true;
        return false;
    }

    public function progress(): Attribute
    {
        $tasksCount = $this->tasks->count();
        if ($tasksCount === 0) {
            $progress = null;
            return Attribute::make(
                get: fn () => $progress,
            );
        }
        $DoneTasksCount = 0;
        foreach ($this->tasks as $task) {
            if ($task->is_done) $DoneTasksCount += 1;
        }
        $progress = round($DoneTasksCount / $tasksCount * 100) . '%';
        return Attribute::make(
            get: fn () => $progress,
        );
    }
}
