<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventBannerRequest extends FormRequest
{
    protected $errorBag = 'event-banner_edit';

    public function rules(): array
    {
        return [
            //
        ];
    }
}
