<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class EventStudent extends Model
{

    public function eventAttended(): HasOne
    {
        return $this->hasOne(EventAttendee::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function fullName(): Attribute 
    {

    /*
        $fullName = preg_replace('!\s+!', ' ', $this->last_name . ' ' . $this->suffix_name . ', ' . $this->first_name . ' ' . $this->middle_name);
        */
        $name = [
            $this->last_name . ',',
            $this->first_name, 
            $this->middle_name . ',',
            $this->suffix_name
        ];
        $nameFiltered = array_filter($name, function ($e) {
            return !preg_match('/^\s*,*\s*$/', $e);
        });
        $nameFormatted = implode(' ', $nameFiltered);
        return Attribute::make(
            get: fn () => $nameFormatted
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
}
