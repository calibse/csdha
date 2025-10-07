<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentSectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'section' => ['required', 'max:10', 
                'unique:App\Models\StudentSection,section']
        ];
    }
}
