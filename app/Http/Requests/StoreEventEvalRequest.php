<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;

class StoreEventEvalRequest extends MultiStepFormRequest
{
    public function rules(): array
    {
        return [
            'overall_satisfaction' => ['required', 'numeric', 'integer', 
                'min:1', 'max:5'],
            'content_relevance' => ['required', 'numeric', 'integer', 'min:1',
                'max:5'],
            'speaker_effectiveness' => ['required', 'numeric', 'integer', 
                'min:1', 'max:5'],
            'engagement_level' => ['required', 'numeric', 'integer', 'min:1',
                'max:5'],
            'duration' => ['required', 'numeric', 'integer', 'in:1,3,5'],
            'topics_covered' => ['required', new MaxText],
            'suggestions_for_improvement' => ['required', new MaxText],
            'future_topics' => ['required', new MaxText],
            'overall_experience' => ['required', new MaxText],
            'additional_comments' => [new MaxText]
        ];
    }
}
