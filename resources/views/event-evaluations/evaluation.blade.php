<x-layout.multi-step-form :$formTitle :$eventName title="Evaluation" :$previousStepRoute>
    <x-alert/>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        <p>
            <label>Overall Satisfaction</label>
            <select name="overall_satisfaction">
                <option value="">-- Select --</option>
                <option value="1" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '1' ? 'selected' : null }}>
                    Very Satisfied</option>
                <option value="2" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '2' ? 'selected' : null }}>
                    Satisfied</option>
                <option value="3" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '3' ? 'selected' : null }}>
                    Neutral</option>
                <option value="4" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '4' ? 'selected' : null }}>
                    Dissatisfied</option>
                <option value="5" {{ (old('overall_satisfaction') ?? ($inputs['overall_satisfaction'] ?? null)) === '5' ? 'selected' : null }}>
                    Very Dissatisfied</option>
            </select>
        </p>
        <p>
            <label>Content Relevance</label>
            <select name="content_relevance">
                <option value="">-- Select --</option>
                <option value="1" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '1' ? 'selected' : null }}>
                    Highly Relevant</option>
                <option value="2" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '2' ? 'selected' : null }}>
                    Relevant</option>
                <option value="3" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '3' ? 'selected' : null }}>
                    Moderately Relevant</option>
                <option value="4" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '4' ? 'selected' : null }}>
                    Slightly Relevant</option>
                <option value="5" {{ (old('content_relevance') ?? ($inputs['content_relevance'] ?? null)) === '5' ? 'selected' : null }}>
                    Not Relevant</option>
            </select>
        </p>
        <p>
            <label>Speaker Effectiveness</label>
            <select name="speaker_effectiveness">
                <option value="">-- Select --</option>
                <option value="1" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '1' ? 'selected' : null }}>
                    Excellent</option>
                <option value="2" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '2' ? 'selected' : null }}>
                    Good</option>
                <option value="3" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '3' ? 'selected' : null }}>
                    Average</option>
                <option value="4" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '4' ? 'selected' : null }}>
                    Fair</option>
                <option value="5" {{ (old('speaker_effectiveness') ?? ($inputs['speaker_effectiveness'] ?? null)) === '5' ? 'selected' : null }}>
                    Poor</option>
            </select>
        </p>
        <p>
            <label>Engagement Level</label>
            <select name="engagement_level">
                <option value="">-- Select --</option>
                <option value="1" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '1' ? 'selected' : null }}>
                    Extremely Engaging</option>
                <option value="2" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '2' ? 'selected' : null }}>
                    Very Engaging</option>
                <option value="3" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '3' ? 'selected' : null }}>
                    Engaging</option>
                <option value="4" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '4' ? 'selected' : null }}>
                    Somewhat Engaging</option>
                <option value="5" {{ (old('engagement_level') ?? ($inputs['engagement_level'] ?? null)) === '5' ? 'selected' : null }}>
                    Not Engaging</option>
            </select>
        </p>
        <p>
            <label>Duration</label>
            <select name="duration">
                <option value="">-- Select --</option>
                <option value="1" {{ (old('duration') ?? ($inputs['duration'] ?? null)) === '1' ? 'selected' : null }}>
                    Too Short</option>
                <option value="2" {{ (old('duration') ?? ($inputs['duration'] ?? null)) === '2' ? 'selected' : null }}>
                    Just Right</option>
                <option value="3" {{ (old('duration') ?? ($inputs['duration'] ?? null)) === '3' ? 'selected' : null }}>
                    Too Long</option>
            </select>
        </p>
        <p>
            <label>Topics Covered</label>
            <textarea name="topics_covered">{{ old('topics_covered') ?? ($inputs['topics_covered'] ?? null) }}</textarea>
        </p>
        <p>
            <label>Suggestions for Improvement</label>
            <textarea name="suggestions_for_improvement">{{ old('suggestions_for_improvement') ?? ($inputs['suggestions_for_improvement'] ?? null) }}</textarea>
        </p>
        <p>
            <label>Future Topics</label>
            <textarea name="future_topics">{{ old('future_topics') ?? ($inputs['future_topics'] ?? null) }}</textarea>
        </p>
        <p>
            <label>Overall Experience</label>
            <textarea name="overall_experience">{{ old('overall_experience') ?? ($inputs['overall_experience'] ?? null) }}</textarea>
        </p>
        <p>
            <label>Additional Comments (optional)</label>
            <textarea name="additional_comments">{{ old('additional_comments') ?? ($inputs['additional_comments'] ?? null) }}</textarea>
        </p>
    </form>
    <x-slot:prevInput>
        <input type="hidden" name="token" value="{{ $token }}">
    </x-slot>
</x-layout>
