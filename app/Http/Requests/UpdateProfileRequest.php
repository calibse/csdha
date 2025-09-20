<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        $uniqueEmail = Rule::unique(User::class, 'email')
            ->ignore(auth()->user()->id);
        $uniqueUsername = Rule::unique(User::class, 'username')
            ->ignore(auth()->user()->id);
        return [
            'avatar' => ['mimetypes:image/jpeg,image/png'],
            'remove_avatar' => ['nullable', 'integer'],
            'username' => ['required', 'max:50', $uniqueUsername],
            // 'email' => ['nullable', 'email', 'max:255', $uniqueEmail],
        ];
    }
}
