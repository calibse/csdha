<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;

class UpdateEventRegisFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'introduction' => [new MaxText]
        ];
    }
}
