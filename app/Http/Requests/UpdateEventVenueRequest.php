<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventVenueRequest extends FormRequest
{
    protected $errorBag = 'event-venue_edit';

    public function rules(): array
    {
        return [
            'venue' => ['max:255'],
        ];
    }
}
