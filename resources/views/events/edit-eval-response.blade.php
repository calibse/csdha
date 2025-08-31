<x-layout.user class="events" :$backRoute title="Edit Attendees Evaluation">
    <article class="article">
        <form method="post" action="{{ $formAction }}">
            {{--
            @foreach (range(1, 4) as $n)
            <p class="checkbox-field">
                <input type="checkbox">
                <label>Calib Serrano</label>
            </p>
            <details class="content">
                <summary>"overall experience"</summary>
                <dl>
                    <dt>Topics Covered:</dt>
                    <dd><pre>Hello</pre></dd>
                </dl>
                <dl>
                    <dt>Suggestions for Improvement:</dt>
                    <dd><pre></pre></dd>
                </dl>
                <dl>
                    <dt>Future Topics:</dt>
                    <dd><pre></pre></dd>
                </dl>
                <dl>
                    <dt>Overall Experience:</dt>
                    <dd><pre></pre></dd>
                </dl>
                <dl>
                    <dt>Additional Comments:</dt>
                    <dd><pre></pre></dd>
                </dl>
                <table class="document">
                    <tbody>
                        <tr>
                            <th>Overall Satisfaction</th>
                            <td>Very Satisfied</td>
                        </tr>
                        <tr>
                            <th>Content Relevance</th>
                            <td>High Relevant</td>
                        </tr>
                        <tr>
                            <th>Speaker Effectiveness</th>
                            <td>Excellent</td>
                        </tr>
                        <tr>
                            <th>Engagement Level</th>
                            <td>Extremely Engaging</td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>Too Short</td>
                        </tr>
                    </tbody>
                </table>
            </details>
            @endforeach
            --}}
        </form>
    </article>
</x-layout.user>
