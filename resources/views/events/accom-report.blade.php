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
                <img src="images/pup-logo.svg"> 
            </div>
            <div class="logo">
                <img src="images/cs-logo.png">
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

    <header class="start-page">
        <div class="start-content">
            <div class="start-title">
                <p class="org-name">COMPUTER SOCIETY</p>
                <p class="school-name">PUP - TAGUIG</p>
            </div>
            <h1 class="event-name">{{ $activity->name }}</h1>
            <p class="year">A.Y. {{ $activity->gpoa->academicPeriod?->year_label }}</p>
        </div>
    </header>
    <main class="content">
        <h2 class="event-name">{{ $activity->name }}</h2>
        <ol class="main-list" type="I">
            <li>
                <h3>Date and Time</h3>
                @foreach ($event->compactDates() as $date)
                <p>{{ $date }}</p>
                @endforeach
            </li>
            <li>
                <h3>Venue</h3>
                <p>{{ $event->venue }}</p>
            </li>
            <li>
                <h3>Type of Activity</h3>
                <p>{{ $activity->type?->name }}</p>
            </li>
            <li>

                <h3>Participants</h3>
                <p>{{ $activity->participants }}</p>
            </li>
            <li>
                <h3>Objectives</h3>
                <pre>{{ $activity->objectives }}</pre>
            </li>
            <li>
                <h3>Description</h3>
                <pre>{{ $event->description }}</pre>
            </li>
            <li>
                <h3>Narrative</h3>
                <pre>{{ $event->narrative }}</pre>
            </li>
            {{-- 
            <li>
                <h3>Attendance</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Attendees</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($event->participantYears()
                            ->orderBy('year', 'asc')->get() as $year)
                        @php
                        $count = $event->attendeesByYear($year->year)->count();
                        $totalAtt += $count;
                        @endphp
                        <tr>
                            <th>{{ ordinal($year->year) }} Year</th>
                            <td>{{ $count }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td>{{ $totalAtt }}</td>
                        </tr>
                    </tfoot>
                </table>
            </li>
            <li>

                <h3> EVALUATION</h3>
                <ul>
                    <li>
                        <h4>Scale</h4>
                    </li>
                    <li>
                        <h4>What are your comments, feedback, or suggestions?</h4>
                        <ul>
                            <li></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <h3> INTERPRETATION OF THE EVALUATION</h3>
                <table class="evaluation-summary">
                    <tr>
                        <th>Overall Satisfaction: </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Content Relevance:</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Speaker Effectiveness: </th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Engagement Level: </th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Duration:</th>
                        <td></td>
                    </tr>
                    <tr class="overall-score">
                        <th>Overall:</th>
                        <td></td>
                    </tr>
                </table>
            </li>
            --}}
            <li>
                <h3>ATTACHMENTS</h3>
            </li>
        <ol>
    </main>
</body>
</html>