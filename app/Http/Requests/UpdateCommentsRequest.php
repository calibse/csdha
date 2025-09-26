<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comments' => ['nullable', 'array'],
            'comments.*' => ['nullable', 'integer'],
        ];
    }
}
