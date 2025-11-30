<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventLinkRequest extends FormRequest
{
    protected $errorBag = "event-link_create";

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'url' => ['required', 'url:http,https', 'max:2000'],
        ];
    }
}
