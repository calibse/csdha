<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreStudentCourseRequest extends FormRequest
{
    protected $errorBag = 'student-course_create';

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255', 
                Rule::unique('App\Models\Course', 'name')->withoutTrashed()
            ],
            'acronym' => ['required', 'max:8',
                Rule::unique('App\Models\Course', 'acronym')->withoutTrashed()
            ]
        ];
    }
}
