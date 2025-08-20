<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Exists;
use App\Models\User;

class SaveAttendeeRequest extends FormRequest
{
    public function rules(): array
    {
        $event = $this->route('event');
        return match ($event->participant_type) {
            'students' => [
                'date' => ['required', 'integer', new Exists($event->dates()
                    ->getQuery(), 'public_id', [])],
                'email' => ['required', 'email', 'max:255'],
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['max:50'],
                'last_name' => ['required', 'max:50'],
                'suffix_name' => ['max:10'],
                'student_id' => ['required', 'max:20', 
                    'regex:/^([A-Z0-9]+)-([A-Z0-9]+)-([A-Z0-9]+)-([A-Z0-9]+)$/'],
                'program' => ['required', 'integer', 'exists:courses,id'],
                'year_level' => ['required', 'integer', 
                    new Exists($event->participants()
                        ->getQuery(), 'id', [])],
                'section' => ['required', 'exists:student_sections,section']
            ],
            'officers' => [
                'date' => ['required', 'integer', new Exists($event->dates()
                    ->getQuery(), 'public_id', [])],
                'officers.*' => [new Exists(User::has('position')
                    ->notOfPosition('adviser'), 'public_id')]
            ]
        };
    }
}
