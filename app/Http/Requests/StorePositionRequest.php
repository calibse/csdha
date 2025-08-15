<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Exists;
use App\Models\User;

class StorePositionRequest extends FormRequest
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
            'position_name' => ['required', 'max:100', 'unique:positions,name'],
            'officer' => ['nullable', 'integer', 
                new Exists(User::query(), 'public_id', ['0'])],
            'permissions.*' => ['nullable', 'integer', 'exists:permissions,id'],
        ];
    }
}
