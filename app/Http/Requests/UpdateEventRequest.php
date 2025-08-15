<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;

class UpdateEventRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tag' => ['max:15'],
            'venue' => ['max:255'],
            'description' => [new MaxText],
            'narrative' => [new MaxText]
        ];
    }
}
