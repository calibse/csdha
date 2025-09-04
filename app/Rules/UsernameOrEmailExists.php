<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;

class UsernameOrEmailExists implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $value)->exists()) return;
        }
        if (User::where('username', $value)->exists()) return;
        $fail('The given :attribute is invalid.');
    }
}
