<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NewPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = request()->input('email');
        $user = auth()->user() ?? User::firstWhere('email', $email);
        if ($user && !Hash::check($value, $user->password)) {
            return;
        }
        $fail('The new password must be different from your current password.');
    }
}
