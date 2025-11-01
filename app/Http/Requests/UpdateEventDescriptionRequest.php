<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;

class UpdateEventDescriptionRequest extends FormRequest
{
    protected $errorBag = 'event-description_edit';

    public function rules(): array
    {
        return [
           'description' => [new MaxText]
        ];
    }
}
