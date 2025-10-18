<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\User;

class UpdateRoleRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
        ];
    }

    public function rules(): array
    {
        return [
            'admin' => ['array'],
            'admin.*' => ['numeric', 'integer', 
                Rule::exists(User::class, 'public_id')
            ]
        ];
    }
}
