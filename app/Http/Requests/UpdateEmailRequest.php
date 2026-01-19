<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateEmailRequest extends FormRequest
{
    protected $errorBag = 'profile-email_edit';

    public function rules(): array
    {
        $uniqueEmail = Rule::unique(User::class, 'email')
            ->ignore(auth()->user()->id);
        if (!is_null(auth()->user()->password)) {
            return [
                'email' => ['required', 'email', 'max:255', $uniqueEmail],
                'password' => ['required', 'current_password:web'],
            ];
        } elseif (auth()->user()->google && is_null(auth()->user()->password)) {
            return [
                'email' => ['required', 'email', 'max:255', $uniqueEmail],
            ];
        }

    }
}
