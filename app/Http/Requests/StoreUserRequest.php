<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['max:50'],
            'last_name' => ['required', 'max:50'],
            'suffix_name' => ['max:50'],
            'email' => ['required', 'email', 'max:255', 
                Rule::unique('App\Models\User', 'email')->withoutTrashed()
            ],
            'username' => ['required', 'max:30', 
                Rule::unique('App\Models\User', 'username')->withoutTrashed()
            ],
            'password' => ['required', 'ascii', 'max:55',  Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols(), 'confirmed']
        ];
    }
}
