@use('App\Services\Format')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Test</title>
    {{--
    @vite(['resources/scss/accom-report.scss'])
    --}}
    <style>
        {!! Vite::content('resources/scss/accom-report.scss') !!}
    </style>
</head>
<body>
    <article id="page-header" class="page-header">
        <div class="logos">
            <div class="logo">
                <img src="storage/app/public/university-logo.png">
            </div>
            <div class="logo">
                <img src="storage/app/public/organization-logo.png">
            </div>
        </div>
        <div class="text">
            <p class="text">Republic of the Philippines</p>
            <p class="main-text">Polytechnic University of the Philippines</p>
            <p class="main-text">Taguig Campus</p>
            <p class="main-text org-name">Computer Society</p>
        </div>
    </article>

    <article id="cover-page" class="cover-page">
        <article class="cover-title">
            <div>
                <p class="org-name">COMPUTER SOCIETY</p>
                <p class="school-name">PUP - TAGUIG</p>
            </div>
        </article>
        <div class="info">
            <div class="credit">
                <p class="title">Prepared by:</p>
                <ul class="people">
                    @foreach ($editors as $editor)
                    <li>
                        <p class="name">{{ $editor->full_name }}</p>
                        <p>CS PUPT {{ $editor->position->name }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
            @if ($approved)
            <div class="credit">
                <p class="title">Approved By:</p>
                <ul class="people">
                    <li>
                        <p>{{ $president->full_name }}</p>
                        <p>CS PUPT {{ $president->position->name }}</p>
                    </li>
                </ul>
            </div>
            @endif
        </div>
    </article>

    @foreach ($events as $event)
    <header class="start-page">
        <div class="start-content">
            <div class="start-title">
                <p class="org-name">COMPUTER SOCIETY</p>
                <p class="school-name">PUP - TAGUIG</p>
            </div>
            <h1 class="event-name">{{ $event['activity']->name }}</h1>
            <p class="year">A.Y. {{ $event['activity']->gpoa->academicPeriod?->year_label }}</p>
        </div>
    </header>
    <main class="content">
        @php $i = 0; @endphp
        <h2 class="event-name">{{ $event['activity']->name }}</h2>
        <section class="date-section">
            <h3>{{ Format::roman(++$i) }}. DATE AND TIME</h3>
            <ul>
            @foreach ($event['event']->compactDates() as $date)
                <li>{{ $date }}</li>
            @endforeach
            </ul>
        </section>
        <section>
            <h3>{{ Format::roman(++$i) }}. VENUE</h3>
            <p>{{ $event['event']->venue }}</p>
        </section>
        <section>
            <h3>{{ Format::roman(++$i) }}. TYPE OF ACTIVITY</h3>
            <p>{{ $event['activity']->type }}</p>
        </section>
        <section>
            <h3>{{ Format::roman(++$i) }}. PARTICIPANTS</h3>
            <p>{{ $event['activity']->participants }}</p>
        </section>
        <section>
            <h3>{{ Format::roman(++$i) }}. OBJECTIVES</h3>
            <pre>{{ $event['activity']->objectives }}</pre>
        </section>
        <section>
            <h3>{{ Format::roman(++$i) }}. DESCRIPTION</h3>
            <pre>{{ $event['event']->description }}</pre>
        </section>
        <section>
            <h3>{{ Format::roman(++$i) }}. NARRATIVE</h3>
            <pre>{{ $event['event']->narrative }}</pre>
        </section>
        @if ($event['attendance']?->isNotEmpty())
        <section>
            <h3>{{ Format::roman(++$i) }}. ATTENDANCE</h3>
            @switch ($event['event']->participant_type)
            @case ('students')
                @switch ($event['attendanceView'])
                @case ('student')
            <table>
                <thead>
                    <tr>
                        <th>Course & Section</th>
                        <th>Attendees</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($event['attendance'] as $attendee)
                    <tr>
                        <td>{{ $attendee->course_section }}</td>
                        <td>{{ $attendee->full_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                    @break
                @case ('year')
            <table>
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Attendees</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($event['attendance'] as $year => $count)
                    <tr>
                        <td>{{ $year }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <td>{{ $event['attendanceTotal'] }}</td>
                    </tr>
                </tfoot>
            </table>
                    @break
                @case ('program')
            <table>
                <thead>
                    <tr>
                        <th>Course & Year</th>
                        <th>Attendees</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($event['attendance'] as $program => $count)
                    <tr>
                        <td>{{ $program }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <td>{{ $event['attendanceTotal'] }}</td>
                    </tr>
                </tfoot>
            </table>
                    @break
                @endswitch
                @break
            @case ('officers')
            <table class="officers-attendance">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($event['attendance'] as $officer)
                    <tr>
                        <td>{{ $officer->position->name }}</td>
                        <td>{{ $officer->full_name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
                @break
            @endswitch
        </section>
        @endif
    @if ($event['event']->accept_evaluation && $event['event']->evaluations()->exists())
        <section>
            <h3>{{ Format::roman(++$i) }}. EVALUATION</h3>
		<div class="list-section">
			<p>Scale:</p>
			<p>5 - Excellent | 4 - Good | 3 - Average | 2 - Fair | 1 - Poor</p>
			<ul>
				<li>Overall rating of the event</li>
				<li>The objectives of the presentation were clear</li>
				<li>Overall experience</li>
			</ul>
		</div>
        @if ($event['comments']->isNotEmpty())
            <div class="list-section">
                <p>What are your comments, feedback, or suggestions?</p>
                <ul class="comment-list">
                @foreach ($event['comments'] as $comment)
                    <li><pre>{{ $comment }}</pre></li>
                @endforeach
                </ul>
            </div>
        @endif
        </section>
        <section>
            <h3>{{ Format::roman(++$i) }}.  INTERPRETATION OF THE EVALUATION</h3>
            <table class="evaluation-summary">
                <tr>
                    <th>Overall Satisfaction: </td>
                    <td>{{ $event['ratings']['os'] }}</td>
                </tr>
                <tr>
                    <th>Content Relevance:</th>
                    <td>{{ $event['ratings']['cr'] }}</td>
                </tr>
                <tr>
                    <th>Speaker Effectiveness: </th>
                    <td>{{ $event['ratings']['se'] }}</td>
                </tr>
                <tr>
                    <th>Engagement Level: </th>
                    <td>{{ $event['ratings']['el'] }}</td>
                </tr>
                <tr>
                    <th>Duration:</th>
                    <td>{{ $event['ratings']['du'] }}</td>
                </tr>
                <tr class="overall-score">
                    <th>Overall:</th>
                    <td>{{ $event['ratings']['overall'] }}</td>
                </tr>
            </table>
        </section>
    @endif
    @if ($event['event']->attachmentSets()->exists())
        <section class="attachment-section">
            <h3>{{ Format::roman(++$i) }}. ATTACHMENTS</h3>
            @php $skip = false; @endphp
            @foreach ($event['event']->attachmentSets()->orderBy('created_at', 'asc')->get() as $set)
                @foreach ($set->attachments()->orderBy('created_at', 'asc')->get() as $i => $attachment)
                    @if ($skip)
                        @php $skip = false; @endphp
                        @continue;
                    @endif
                    @php $next = $set->attachments[$i + 1] ?? null; @endphp
            <div class="attachment">
                <img class="{{ $attachment->orientation }} {{ $attachment->full_width ? 'full-width' : null }}" src="storage/app/private/{{ $attachment->image_filepath }}">
                    @if (!($attachment->standalone || $attachment->full_width) && in_array($attachment->orientation, ['portrait', 'square']) && $next && in_array($next->orientation, ['portrait', 'square']) && !($next->standalone || $next->full_width))
                <img class="{{ $next->orientation }}" src="storage/app/private/{{ $next->image_filepath }}">
                        @php $skip = true; @endphp
                    @endif
            </div>
                @endforeach
            <p class="caption">{{ $set->caption }}</p>
            @endforeach
        </section>
    @endif
    </main>
    @endforeach
</body>
</html>
