<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\MultiStepClassMap;

class StoreMultiStepFormResponseRequest extends FormRequest
{
    public function rules(): array
    {
        $currentRoute = $this->route()->getName();
        $stepClass = MultiStepClassMap::getClass($currentRoute);
        return (new $stepClass($currentRoute))->rules(); 
    }
}
