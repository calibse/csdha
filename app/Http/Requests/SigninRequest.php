<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UsernameOrEmailExists;

class SigninRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'username' => 'username or email'
        ];
    }

    public function rules(): array
    {
        return [
            'username' => ['required'],
            'password' => ['required', 'max:55']
        ];
    }
}
