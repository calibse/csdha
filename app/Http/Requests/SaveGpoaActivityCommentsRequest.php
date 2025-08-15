<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;

class SaveGpoaActivityCommentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comments' => [new MaxText]
        ];
    }
}
