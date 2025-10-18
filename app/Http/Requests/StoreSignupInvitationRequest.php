<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Exists;
use App\Models\Position;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\SignupInvitation;

class StoreSignupInvitationRequest extends FormRequest
{
    public function rules(): array
    {
        $uniqueFromUsers = Rule::unique(User::class, 'email')
            ->withoutTrashed();
        $uniqueFromInvitations = Rule::unique(SignupInvitation::class, 'email');
        return [
            'position' => ['required', 'numeric', 'integer', 
                new Exists(Position::open(), 'id', [0])],
            'email' => ['required', 'email', $uniqueFromUsers, 
                $uniqueFromInvitations]
        ];
    }
}
