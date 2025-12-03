<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccomReportBackgroundRequest extends FormRequest
{
    protected $errorBag = 'accom-report-background_edit';

    public function rules(): array
    {
        return [
            'remove_background' => ['boolean'],
            'background_file' => ['exclude_if:remove_background,true', 
                'required', 'mimetypes:image/jpeg,image/png,'
                .'image/webp,image/avif']
        ];
    }
}
