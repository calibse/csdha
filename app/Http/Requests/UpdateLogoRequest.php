<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLogoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'website' => ['mimetypes:image/svg+xml,image/png,'
                .'image/webp,image/avif'],
            'organization' => ['mimetypes:image/svg+xml,image/png,'
                .'image/webp,image/avif'],
            'university' => ['mimetypes:image/svg+xml,image/png,'
                .'image/webp,image/avif']
        ];
    }
}
