<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;
use App\Rules\Exists;
use App\Models\StudentYear;
use Illuminate\Validation\Rule;
use DateTimeZone;

class UpdateEventRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'record_attendance.*' => 'record attendance',
        ];
    }

    public function rules(): array
    {
        $timezones = DateTimeZone::listIdentifiers();
        return [
            'record_attendance' => ['array'],
            'record_attendance.*' => [new Exists(StudentYear::query(), 'id',
                ['0', '-1'])],
            'student_courses' => [
                'exclude_without:record_attendance',
                Rule::excludeIf(array_intersect($this->record_attendance ?? [], 
                    ['0', '-1']) ? true : false),
                'required', 'array'
            ],
            'student_courses.*' => ['numeric', 'integer', 
                Rule::exists('App\Models\Course', 'id')->withoutTrashed(),
            ],
            'automatic_attendance' => ['boolean'],
            'accept_evaluation' => ['boolean'],
            'tag' => ['max:15'],
            'timezone' => ['required', Rule::in($timezones)],
            'evaluation_delay_hours' => [
                Rule::excludeIf(array_intersect($this->record_attendance ?? [], 
                    ['0', '-1']) ? true : false),
                'required', 'min:0', 'max:168',
            ],
        ];
    }
}
