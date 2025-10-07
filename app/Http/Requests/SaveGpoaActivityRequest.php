<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;
use App\Rules\Exists;
use App\Models\StudentYear;
use App\Models\User;

class SaveGpoaActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'participant_year_levels.*' => 'participant year levels',
            'event_heads' => 'event heads',
            'coheads' => 'co-heads'
        ];
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'objectives' => ['required', new MaxText],
            'participants_description' => ['required', 'max:100'],
            'type_of_activity' => ['required', 'max:255'],
            'mode' => ['required', 'max:50'],
            'partnership' => ['max:255'],
            'proposed_budget' => ['nullable', 'numeric', 'min:0', 
                'max:999999.99'],
            'fund_source' => ['max:255'],
            'event_heads.*' => ['nullable', new Exists(User::has('position')
                ->notAuthUser()->notOfPosition(['adviser']), 'public_id', 
                ['0'])],
            'coheads.*' => ['nullable', new Exists(User::has('position')
                ->notAuthUser()->notOfPosition(['adviser']), 'public_id', 
                ['0'])]
        ];
    }
}
