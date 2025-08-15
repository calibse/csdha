<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class EventAttendance extends Model
{
    public function eventDate(): BelongsTo
    {
        return $this->belongsTo(EventDate::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function sectionModel(): BelongsTo
    {
        return $this->belongsTo(StudentSection::class, 'student_section_id');
    }

    protected function section(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->sectionModel->section,
            set: fn ($value) => [
                'student_section_id' => StudentSection
                    ::firstWhere('section', '=', $value)->id
            ]
        );
    }

    public function yearModel(): BelongsTo
    {
        return $this->belongsTo(StudentYear::class, 'student_year_id');
    }

    protected function year(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->yearModel->year,
            set: fn ($value) => [
                'student_year_id' => StudentYear
                    ::firstWhere('year', '=', $value)->id
            ]
        );
    }

    public function courseSection(): Attribute 
    {
        $courseSection = preg_replace('!\s+!', ' ', $this->course->acronym . 
        " " . $this->year . " - " . $this->section);
        return Attribute::make(
            get: fn () => $courseSection,
        );
    }

    public function entryTime(): Attribute 
    {
        $time = Carbon::parse($this->created_at)->tz('Asia/Manila')
            ->format('g:i A');
        return Attribute::make(
            get: fn () => $time
        );
    }
}
