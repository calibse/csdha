<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAttachmentSetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'caption' => ['required', 'max:255'],
            'images.*' => ['mimetypes:image/jpeg,image/png']
        ];
    }
}
