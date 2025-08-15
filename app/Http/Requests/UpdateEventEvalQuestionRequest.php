<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxText;

class UpdateEventEvalQuestionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'introduction' => [new MaxText],
            'overall_satisfaction' => ['max:255'],
            'content_relevance' => ['max:255'],
            'speaker_effectiveness' => ['max:255'],
            'engagement_level' => ['max:255'],
            'duration' => ['max:255'],
            'topics_covered' => ['max:255'],
            'suggestions_for_improvement' => ['max:255'],
            'future_topics' => ['max:255'],
            'overall_experience' => ['max:255'],
            'additional_comments' => ['max:255'],
            'acknowledgement' => [new MaxText]
        ];
    }
}
