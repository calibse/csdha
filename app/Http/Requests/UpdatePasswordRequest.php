<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'current_password:web'],
            'password_confirmation' => ['required'],
            'password' => ['required', 'ascii', 'max:55',  Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols(), 'confirmed']
        ];
    }
}
