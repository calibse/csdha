<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;
use App\Rules\Exists;
use App\Models\StudentYear;

class UpdateEventRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'record_attendance.*' => 'record attendance',
        ];
    }

    public function rules(): array
    {
        return [
            'record_attendance' => ['array'],
            'record_attendance.*' => [new Exists(StudentYear::query(), 'id',
                ['0', '-1'])],
            'automatic_attendance' => ['boolean'],
            'tag' => ['max:15'],
            'venue' => ['max:255'],
            'description' => [new MaxText],
            'narrative' => [new MaxText]
        ];
    }
}
