<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsentRequest extends MultiStepFormRequest
{
    public function rules(): array
    {
        return [
            'consent' => ['required', 'accepted']
        ];
    }
}
