<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventBannerRequest extends FormRequest
{
    protected $errorBag = 'event-banner_edit';

    public function rules(): array
    {
        return [
            'remove_banner' => ['boolean'],
            'banner' => ['exclude_if:remove_banner,true',
                'required', 'mimetypes:image/jpeg,image/png,'
                .'image/webp,image/avif']
        ];
    }
}
