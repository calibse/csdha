<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\EvalFormStep;

class StoreEvalFormResponseRequest extends FormRequest
{
    public function rules(): array
    {
        return (new EvalFormStep($this->route()->getName()))->rules(); 
    }
}
