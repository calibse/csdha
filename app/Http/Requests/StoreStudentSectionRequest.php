<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentSectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'section' => ['required', 'max:10', 
                Rule::unique('App\Models\StudentSection', 'section')
                    ->withoutTrashed()]
        ];
    }
}
