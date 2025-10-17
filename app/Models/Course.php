<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use SoftDeletes;

    public function student(): HasMany 
    {
        return $this->hasMany(Student::class);
    }

    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function events(): BelongsToMany 
    {
        return $this->belongsToMany(Event::class, 'event_participant_courses');
    }
}
