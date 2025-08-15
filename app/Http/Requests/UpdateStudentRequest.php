<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function rules(): array
    {
        $student = $this->route('student');
        return [
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['max:50'],
            'last_name' => ['required', 'max:50'],
            'suffix_name' => ['max:10'],
            'course' => ['required', 'numeric', 'exists:courses,id'],
            'year' => ['required', 'numeric', 'exists:student_years,year'],
            'section' => ['required', 'exists:student_sections,section'],
            'email' => ['required', 'email', Rule::unique('students', 'email')
                ->ignore($student->id)]
        ];
    }

    public function messages(): array
    {
        return [
            'course.exists' => 'The entered course is invalid.',
            'year.exists' => 'The entered year is invalid.',
            'section.exists' => 'The entered section is invalid.',
        ];
    }
}
