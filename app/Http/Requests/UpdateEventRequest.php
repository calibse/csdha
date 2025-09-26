<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;
use App\Rules\Exists;
use App\Models\StudentYear;
use Illuminate\Validation\Rule;
use DateTimeZone;

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
        $timezones = DateTimeZone::listIdentifiers();
        return [
            'record_attendance' => ['array'],
            'record_attendance.*' => [new Exists(StudentYear::query(), 'id',
                ['0', '-1'])],
            'automatic_attendance' => ['boolean'],
            'accept_evaluation' => ['boolean'],
            'tag' => ['max:15'],
            'venue' => ['max:255'],
            'timezone' => ['required', Rule::in($timezones)],
            'description' => [new MaxText],
            'narrative' => [new MaxText]
        ];
    }
}
