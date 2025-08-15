<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveGpoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_term' => ['required', 'numeric', 'exists:App\Models\AcademicTerm,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date']
        ];
    }
}
