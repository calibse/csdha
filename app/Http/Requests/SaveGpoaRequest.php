<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveGpoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_term' => ['required', 'integer', 'numeric', 
                Rule::exists('App\Models\AcademicTerm', 'id')
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'head_of_student_services' => ['required', 'max:100'],
            'branch_director' => ['required', 'max:100'],
        ];
    }
}
