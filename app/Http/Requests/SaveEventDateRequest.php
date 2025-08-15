<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveEventDateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i']
        ];
    }

    public function messages(): array
    {
        return [
            'start_time' => 'The start time field must match the 24-hour format.', 
            'end_time' => 'The end time field must match the 24-hour format.', 
        ];
    }
}
