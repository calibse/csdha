<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StudentYear extends Model
{
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_participants');
    }

    public function gspoaEvents(): BelongsToMany
    {
        return $this->belongsToMany(GSPOAEvent::class, 
            'gspoa_event_participants');
    }

}
