<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Exists;

class StoreEventEvalIdentityRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'max:20'],
        ];
    }
}
