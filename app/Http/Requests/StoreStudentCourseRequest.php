<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255', 
                'unique:App\Models\Course,name'],
            'acronym' => ['required', 'max:8',
                'unique:App\Models\Course,acronym']
        ];
    }
}
