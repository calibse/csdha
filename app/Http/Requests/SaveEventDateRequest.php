<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveEventDateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time']
        ];
    }

    public function messages(): array
    {
        return [
            'start_time.date_format' => 'The start time field must match the 24-hour format.', 
            'end_time.date_format' => 'The end time field must match the 24-hour format.', 
        ];
    }
}
