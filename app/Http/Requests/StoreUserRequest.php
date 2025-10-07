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
            'email' => ['required', 'max:255', 
                'unique:App\Models\User,email'],
            'username' => ['required', 'max:30', 
                'unique:App\Models\User,username'],
            'password_confirmation' => ['required'],
            'password' => ['required', 'ascii', 'max:55',  Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols(), 
                'confirmed']
        ];
    }
}
