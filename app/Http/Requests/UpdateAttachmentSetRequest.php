<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttachmentSetRequest extends FormRequest
{
    protected $errorBag = "event-attachment-set_edit";

    public function rules(): array
    {
        return [
            'caption' => ['required', 'max:255'],
            'images.*' => ['mimetypes:image/jpeg,image/png']
        ];
    }
}
