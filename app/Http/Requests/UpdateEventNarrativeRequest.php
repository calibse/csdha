<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;

class UpdateEventNarrativeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'narrative' => [new MaxText]
        ];
    }
}
