<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'max:20', 'exists:App\Models\Student,student_id']
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.exists' => 'The entered student ID does not match '
                . 'any existing records.'
        ];
    }
}
