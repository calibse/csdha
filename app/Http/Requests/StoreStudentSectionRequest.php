<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentSectionRequest extends FormRequest
{
    protected $errorBag = 'student-section_create';

    public function rules(): array
    {
        return [
            'section' => ['required', 'max:10', 
                Rule::unique('App\Models\StudentSection', 'section')
                    ->withoutTrashed()]
        ];
    }
}
