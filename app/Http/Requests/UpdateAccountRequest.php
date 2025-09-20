<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class UpdateAccountRequest extends FormRequest
{
    public function rules(): array
    {
        $account = $this->route('account');
        $uniqueEmail = Rule::unique(User::class, 'email')
            ->ignore($account->id);
        $uniqueUsername = Rule::unique(User::class, 'username')
            ->ignore($account->id);
        return [
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['max:50'],
            'last_name' => ['required', 'max:50'],
            'suffix_name' => ['max:50'],
            // 'email' => ['nullable', $uniqueEmail],
            // 'username' => ['required', $uniqueUsername]
        ];
    }
}
