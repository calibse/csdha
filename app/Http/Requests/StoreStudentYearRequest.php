<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreStudentYearRequest extends FormRequest
{
    protected $errorBag = 'student-year-level_create';

    public function rules(): array
    {
        return [
            'year_level' => ['required', 'max:4', 
                Rule::unique('App\Models\StudentYear', 'year')->withoutTrashed()
            ],
            'label' => ['required', 'max:15']
        ];
    }
}
