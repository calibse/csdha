<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\Exists;
use App\Models\User;

class UpdatePositionRequest extends FormRequest
{
    public function attributes(): array
    {
        return [
            'permissions.*' => 'permissions'
        ];
    }

    public function rules(): array
    {
        $position = $this->route('position');
        return [
            'position_name' => ['required', 'max:100', 
                 Rule::unique('positions', 'name')->ignore($position->id)],
            'officer' => ['nullable', 'integer', 
                new Exists(User::query(), 'public_id', ['0'])],
            'permissions.*' => ['nullable', 'integer', 'exists:permissions,id'],
        ];
    }
}
