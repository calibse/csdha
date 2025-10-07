<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentYearRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'year' => ['required', 'max:4', 
                'unique:App\Models\StudentYear,year'],
            'label => ['required', 'max:15']
        ];
    }
}
