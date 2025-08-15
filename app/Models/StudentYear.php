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
        return $this->belongsToMany(StudentYear::class, 'event_attendances')
            ->as('attendance')
            ->withPivot(
                'course_id',
                'student_year_id',
                'student_section_id'
            )
            ->using(EventAttendance::class);
    }

    public function gspoaEvents(): BelongsToMany
    {
        return $this->belongsToMany(GSPOAEvent::class, 
            'gspoa_event_participants');
    }

}
