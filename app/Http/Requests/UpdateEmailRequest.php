<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateEmailRequest extends FormRequest
{
    public function rules(): array
    {
        $uniqueEmail = Rule::unique(User::class, 'email')
            ->ignore(auth()->user()->id);
        return [
            'email' => ['required', 'email', 'max:255', $uniqueEmail],
            'password' => ['required', 'current_password:web'],
        ];
    }
}
