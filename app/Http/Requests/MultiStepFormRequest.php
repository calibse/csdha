<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class MultiStepFormRequest extends FormRequest
{
    protected function getRedirectUrl()
    {
        $url = parent::getRedirectUrl();
        return $url . '#content';
    }
}
