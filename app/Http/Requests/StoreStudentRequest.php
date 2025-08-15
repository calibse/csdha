<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'max:20', 'unique:students,student_id'],
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['max:50'],
            'last_name' => ['required', 'max:50'],
            'suffix_name' => ['max:10'],
            'course' => ['required', 'numeric', 'exists:courses,id'],
            'year' => ['required', 'numeric', 'exists:student_years,year'],
            'section' => ['required', 'exists:student_sections,section'],
            'email' => ['required', 'email', 'unique:students,email']
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'The student ID field is required.',
            'course.exists' => 'The entered course is invalid.',
            'year.exists' => 'The entered year is invalid.',
            'section.exists' => 'The entered section is invalid.',
        ];
    }
}
