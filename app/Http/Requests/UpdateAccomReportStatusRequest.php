<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\MaxText;

class UpdateAccomReportStatusRequest extends FormRequest
{
    protected $errorBag = 'accom-report_prepare';

    public function rules(): array
    {
        return [
            'comments' => ['required', new MaxText]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        session()->flash('form_action_url', $this->url());
        parent::failedValidation($validator);
    }
}
