@php
$ratings = [
    'overall_satisfaction' => [
        'Very Satisfied',
        'Satisfied',
        'Neutral',
        'Dissatisfied',
        'Very Dissatisfied',
    ],
    'content_relevance' => [
        'Highly Relevant',
        'Relevant',
        'Moderately Relevant',
        'Slightly Relevant',
        'Not Relevant',
    ],
    'speaker_effectiveness' => [
        'Excellent',
        'Good',
        'Average',
        'Fair',
        'Poor',
    ],
    'engagement_level' => [
        'Extremely Engaging',
        'Very Engaging',
        'Engaging',
        'Somewhat Engaging',
        'Not Engaging',
    ],
    'duration' => [
        'Too Short',
        'Just Right',
        'Too Long',
    ],
];
@endphp
<x-layout.user class="events evaluations" :$backRoute title="Edit Attendees Evaluation">
    <article class="article">
        <form method="post" action="{{ $formAction }}">
            <p>Evaluations</p>
            <ul>
                @foreach ($evaluations as $eval)
                <li>
                    <p class="checkbox-field">
                        <input type="checkbox">
                        <label>{{ $eval->attendee->student->full_name }}</label>
                    </p>
                    <details class="content">
                        <summary>Show</summary>
                        <table class="document alt">
                            <colgroup>
                                <col style="width: 5rem">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>Topics Covered</th>
                                    <td><pre>{{ $eval->topics_covered }}</pre></td>
                                </tr>
                                <tr>
                                    <th>Suggestions for Improvement</th>
                                    <td><pre>{{ $eval->suggestions_for_improvement }}</pre></td>
                                </tr>
                                <tr>
                                    <th>Future Topics</th>
                                    <td><pre>{{ $eval->future_topics }}</pre></td>
                                </tr>
                                <tr>
                                    <th>Overall Experience</th>
                                    <td><pre>{{ $eval->overall_experience }}</pre></td>
                                </tr>
                                <tr>
                                    <th>Additional Comments</th>
                                    <td><pre>{{ $eval->additional_comments }}</pre></td>
                                </tr>
                                <tr>
                                    <th>Overall Satisfaction</th>
                                    <td>{{ $ratings['overall_satisfaction'][$eval->overall_satisfaction] }}</td>
                                </tr>
                                <tr>
                                    <th>Content Relevance</th>
                                    <td>{{ $ratings['content_relevance'][$eval->content_relevance] }}</td>
                                </tr>
                                <tr>
                                    <th>Speaker Effectiveness</th>
                                    <td>{{ $ratings['speaker_effectiveness'][$eval->speaker_effectiveness] }}</td>
                                </tr>
                                <tr>
                                    <th>Engagement Level</th>
                                    <td>{{ $ratings['engagement_level'][$eval->engagement_level] }}</td>
                                </tr>
                                <tr>
                                    <th>Duration</th>
                                    <td>{{ $ratings['duration'][$eval->duration] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </details>
                </li>
                @endforeach
            </ul>
        </form>
    </article>
</x-layout.user>
