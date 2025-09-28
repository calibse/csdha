<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;

class StoreEventEvalRequest extends MultiStepFormRequest
{
    public function rules(): array
    {
        return [
            'overall_satisfaction' => ['required', 'integer', 'min:1',
                'max:5'],
            'content_relevance' => ['required', 'integer', 'min:1',
                'max:5'],
            'speaker_effectiveness' => ['required', 'integer', 'min:1',
                'max:5'],
            'engagement_level' => ['required', 'integer', 'min:1',
                'max:5'],
            'duration' => ['required', 'integer', 'min:1', 'max:3'],
            'topics_covered' => ['required', new MaxText],
            'suggestions_for_improvement' => ['required', new MaxText],
            'future_topics' => ['required', new MaxText],
            'overall_experience' => ['required', new MaxText],
            'additional_comments' => [new MaxText]
        ];
    }
}
