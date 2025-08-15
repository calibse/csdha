<x-layout.user class="events form" :$backRoute title="Edit Evaluation Form">
    <article class="article">
        <form method="post" action="{{ $formAction }}">
            @method('PUT')
            @csrf
            <p>
                <label>Introduction</label>
                <textarea name="introduction">{{ old('introduction') ?? $question?->introduction }}</textarea>
            </p>
            <fieldset>
                <legend>Evaluation Questions</legend>
                <p>
                    <label>Overall Satisfaction</label>
                    <input name="overall_satisfaction" value="{{ old('overall_satisfaction') ?? $question?->overall_satisfaction }}">
                </p>
                <p>
                    <label>Content Relevance</label>
                    <input name="content_relevance" value="{{ old('content_relevance') ?? $question?->content_relevance }}">
                </p>
                <p>
                    <label>Speaker Effectiveness</label>
                    <input name="speaker_effectiveness" value="{{ old('speaker_effectiveness') ?? $question?->speaker_effectiveness }}">
                </p>
                <p>
                    <label>Engagement Level</label>
                    <input name="engagement_level" value="{{ old('engagement_level') ?? $question?->engagement_level }}">
                </p>
                <p>
                    <label>Duration</label>
                    <input name="duration" value="{{ old('duration') ?? $question?->duration }}">
                </p>
                <p>
                    <label>Topics Covered</label>
                    <input name="topics_covered" value="{{ old('topics_covered') ?? $question?->topics_covered }}">
                </p>
                <p>
                    <label>Suggestions for Improvement</label>
                    <input name="suggestions_for_improvement" value="{{ old('suggestions_for_improvement') ?? $question?->suggestions_for_improvement }}">
                </p>
                <p>
                    <label>Future Topics</label>
                    <input name="future_topics" value="{{ old('future_topics') ?? $question?->future_topics }}">
                </p>
                <p>
                    <label>Overall Experience</label>
                    <input name="overall_experience" value="{{ old('overall_experience') ?? $question?->overall_experience }}">
                </p>
                <p>
                    <label>Additional Comments</label>
                    <input name="additional_comments" value="{{ old('additional_comments') ?? $question?->additional_comments }}">
                </p>
            </fieldset>
            <p>
                <label>Acknowledgement</label>
                <textarea name="acknowledgement">{{ old('acknowledgement') ?? $question?->acknowledgement }}</textarea>
            </p>
            <p class="form-submit">
                <button>Update</button>
            </p>
        </form>
    </article>
</x-layout.user>
