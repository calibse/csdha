<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateAccomReportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date' => ['required', 'sometimes', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date']
        ];
    }
}
