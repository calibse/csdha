<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    protected $errorBag = 'profile-password_edit';

    public function rules(): array
    {
	    $requiredRule = 'required';
	    if (!auth()->user()->password) {
		    $requiredRule = 'nullable';
        }
        return [
            'old_password' => [$requiredRule, 'current_password:web'],
            'password' => ['required', 'ascii', 'max:55',  Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols(), 'confirmed']
        ];
    }
}
