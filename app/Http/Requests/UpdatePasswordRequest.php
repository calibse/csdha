<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordRequest extends FormRequest
{
    public function rules(): array
    {
	$requiredRule = 'required';
	if (!auth()->user()->password && auth()->user()->google) {
		$requiredRule = 'nullable';
        }
        return [
            'old_password' => [$requiredRule, 'current_password:web'],
            'password' => ['required', 'ascii', 'max:55',  Password::min(8)
                ->letters()->mixedCase()->numbers()->symbols(), 'confirmed']
        ];
    }
}
