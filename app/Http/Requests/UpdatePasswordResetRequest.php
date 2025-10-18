<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Rules\NewPassword;

class UpdatePasswordResetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', 'ascii', 'max:55',  Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols(), new NewPassword,
                'confirmed']
        ];
    }
}
