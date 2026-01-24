<x-layout.event-evaluation-form class="event-evaluation multi-step-form" :$event :$step :$completeSteps :$routes :$isPreview>
    <x-alert/>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        <fieldset>
            <legend>{{ $form?->overall_satisfaction ?? 'Overall Satisfaction' }}</legend>
            <p class="checkbox">
                <input id="overall-satisfaction-1" name="overall_satisfaction" type="radio" value="5" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '5' ? 'checked' : null }}>
                <label for="overall-satisfaction-1">Very Satisfied</label>
            </p>
            <p class="checkbox">
                <input id="overall-satisfaction-2" name="overall_satisfaction" type="radio" value="4" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '4' ? 'checked' : null }}>
                <label for="overall-satisfaction-2">Satisfied</label>
            </p>
            <p class="checkbox">
                <input id="overall-satisfaction-3" name="overall_satisfaction" type="radio" value="3" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '3' ? 'checked' : null }}>
                <label for="overall-satisfaction-3">Neutral</label>
            </p>
            <p class="checkbox">
                <input id="overall-satisfaction-4" name="overall_satisfaction" type="radio" value="2" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '2' ? 'checked' : null }}>
                <label for="overall-satisfaction-4">Dissatisfied</label>
            </p>
            <p class="checkbox">
                <input id="overall-satisfaction-5" name="overall_satisfaction" type="radio" value="1" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '1' ? 'checked' : null }}>
                <label for="overall-satisfaction-5">Very Dissatisfied</label>
            </p>
        </fieldset>
        <fieldset>
            <legend>{{ $form?->content_relevance ?? 'Content Relevance' }}</legend>
            <p class="checkbox">
                <input id="content-relevance-1" name="content_relevance" type="radio" value="5" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '5' ? 'checked' : null }}>
                <label for="content-relevance-1">Highly Relevant</label>
            </p>
            <p class="checkbox">
                <input id="content-relevance-2" name="content_relevance" type="radio" value="4" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '4' ? 'checked' : null }}>
                <label for="content-relevance-2">Relevant</label>
            </p>
            <p class="checkbox">
                <input id="content-relevance-3" name="content_relevance" type="radio" value="3" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '3' ? 'checked' : null }}>
                <label for="content-relevance-3">Moderately Relevant</label>
            </p>
            <p class="checkbox">
                <input id="content-relevance-4" name="content_relevance" type="radio" value="2" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '2' ? 'checked' : null }}>
                <label for="content-relevance-4">Slightly Relevant</label>
            </p>
            <p class="checkbox">
                <input id="content-relevance-5" name="content_relevance" type="radio" value="1" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '1' ? 'checked' : null }}>
                <label for="content-relevance-5">Not Relevant</label>
            </p>
        </fieldset>
        <fieldset>
            <legend>{{ $form?->speaker_effectiveness ?? 'Speaker Effectiveness' }}</legend>
            <p class="checkbox">
                <input id="speaker-effectiveness-1" name="speaker_effectiveness" type="radio" value="5" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '5' ? 'checked' : null }}>
                <label for="speaker-effectiveness-1">Excellent</label>
            </p>
            <p class="checkbox">
                <input id="speaker-effectiveness-2" name="speaker_effectiveness" type="radio" value="4" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '4' ? 'checked' : null }}>
                <label for="speaker-effectiveness-2">Good</label>
            </p>
            <p class="checkbox">
                <input id="speaker-effectiveness-3" name="speaker_effectiveness" type="radio" value="3" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '3' ? 'checked' : null }}>
                <label for="speaker-effectiveness-3">Average</label>
            </p>
            <p class="checkbox">
                <input id="speaker-effectiveness-4" name="speaker_effectiveness" type="radio" value="2" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '2' ? 'checked' : null }}>
                <label for="speaker-effectiveness-4">Fair</label>
            </p>
            <p class="checkbox">
                <input id="speaker-effectiveness-5" name="speaker_effectiveness" type="radio" value="1" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '1' ? 'checked' : null }}>
                <label for="speaker-effectiveness-5">Poor</label>
            </p>
        </fieldset>
        <fieldset>
            <legend>{{ $form?->engagement_level ?? 'Engagement Level' }}</legend>
            <p class="checkbox">
                <input id="engagement-level-1" name="engagement_level" type="radio" value="5" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '5' ? 'checked' : null }}>
                <label for="engagement-level-1">Extremely Engaging</label>
            </p>
            <p class="checkbox">
                <input id="engagement-level-2" name="engagement_level" type="radio" value="4" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '4' ? 'checked' : null }}>
                <label for="engagement-level-2">Very Engaging</label>
            </p>
            <p class="checkbox">
                <input id="engagement-level-3" name="engagement_level" type="radio" value="3" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '3' ? 'checked' : null }}>
                <label for="engagement-level-3">Engaging</label>
            </p>
            <p class="checkbox">
                <input id="engagement-level-4" name="engagement_level" type="radio" value="2" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '2' ? 'checked' : null }}>
                <label for="engagement-level-4">Somewhat Engaging</label>
            </p>
            <p class="checkbox">
                <input id="engagement-level-5" name="engagement_level" type="radio" value="1" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '1' ? 'checked' : null }}>
                <label for="engagement-level-5">Not Engaging</label>
            </p>
        </fieldset>
        <fieldset>
            <legend>{{ $form?->duration ?? 'Duration' }}</legend>
            <p class="checkbox">
                <input id="duration-1" name="duration" type="radio" value="1" {{ (old('duration') ?? ($inputs['duration'] ?? null)) === '1' ? 'checked' : null }}>
                <label for="duration-1">Too Short</label>
            </p>
            <p class="checkbox">
                <input id="duration-2" name="duration" type="radio" value="5" {{ (old('duration') ?? ($inputs['duration'] ?? null)) === '5' ? 'checked' : null }}>
                <label for="duration-2">Just Right</label>
            </p>
            <p class="checkbox">
                <input id="duration-3" name="duration" type="radio" value="3" {{ (old('duration') ?? ($inputs['duration'] ?? null)) === '3' ? 'checked' : null }}>
                <label for="duration-3">Too Long</label>
            </p>
        </fieldset>
        <p>
            <label>{{ $form?->topics_covered ?? 'Topics Covered' }}</label>
            <textarea name="topics_covered">{{ $errors->any() ? old('topics_covered') : ($inputs['topics_covered'] ?? null) }}</textarea>
        </p>
        <p>
            <label>{{ $form?->suggestions_for_improvement ?? 'Suggestions for Improvement' }}</label>
            <textarea name="suggestions_for_improvement">{{ $errors->any() ? old('suggestions_for_improvement') : ($inputs['suggestions_for_improvement'] ?? null) }}</textarea>
        </p>
        <p>
            <label>{{ $form?->future_topics ?? 'Future Topics' }}</label>
            <textarea name="future_topics">{{ $errors->any() ? old('future_topics') : ($inputs['future_topics'] ?? null) }}</textarea>
        </p>
        <p>
            <label>{{ $form?->overall_experience ?? 'Overall Experience' }}</label>
            <textarea name="overall_experience">{{ $errors->any() ? old('overall_experience') : ($inputs['overall_experience'] ?? null) }}</textarea>
        </p>
        <p>
            <label>{{ $form?->additional_comments ?? 'Additional Comments' }} (optional)</label>
            <textarea name="additional_comments">{{ $errors->any() ? old('additional_comments') : ($inputs['additional_comments'] ?? null) }}</textarea>
        </p>
        <p class="form-submit">
            <button>Next</button>
        </p>
    </form>
</x-layout>
