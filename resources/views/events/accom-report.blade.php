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
                <img src="assets/images/pup-logo.svg"> 
            </div>
            <div class="logo">
                <img src="assets/images/cs-logo.png">
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
        <h2 class="event-name">{{ $event['activity']->name }}</h2>
        <ol class="main-list" type="I">
            <li>
                <h3>DATE AND TIME</h3>
                @foreach ($event['event']->compactDates() as $date)
                <p>{{ $date }}</p>
                @endforeach
            </li>
            <li>
                <h3>VENUE</h3>
                <p>{{ $event['event']->venue }}</p>
            </li>
            <li>
                <h3>TYPE OF ACTIVITY</h3>
                <p>{{ $event['activity']->type?->name }}</p>
            </li>
            <li>

                <h3>PARTICIPANTS</h3>
                <p>{{ $event['activity']->participants }}</p>
            </li>
            <li>
                <h3>OBJECTIVES</h3>
                <pre>{{ $event['activity']->objectives }}</pre>
            </li>
            <li>
                <h3>DESCRIPTION</h3>
                <pre>{{ $event['event']->description }}</pre>
            </li>
            <li>
                <h3>NARRATIVE</h3>
                <pre>{{ $event['event']->narrative }}
                    Google’s grip on Android keeps tightening. In what will certainly be another step that we will look back upon as just another nail in the coffin, Google is going to require every Android developer to register with Google, even if they don’t publish anything in the Play Store. In other words, even if you develop Android applications ad only make them available through F-Droid or GitHub, you’ll still have to register with Google and hand over a bunch of personal information and a small fee of $25. Google is effectively recreating Apple’s Gatekeeper for macOS, but on Android.

It won’t come as a surprise to you that Google is doing this in the name of security and protecting users. The company claims that its own analysis found “over 50 times more malware from internet-sideloaded sources than on apps available through Google Play”, and the main reason is that malware developers can hide behind anonymity. As such, Google’s solution is to simply deanonymise every single Android developer.

    Starting next year, Android will require all apps to be registered by verified developers in order to be installed by users on certified Android devices. This creates crucial accountability, making it much harder for malicious actors to quickly distribute another harmful app after we take the first one down. Think of it like an ID check at the airport, which confirms a traveler’s identity but is separate from the security screening of their bags; we will be confirming who the developer is, not reviewing the content of their app or where it came from. This change will start in a few select countries specifically impacted by these forms of fraudulent app scams, often from repeat perpetrators.
    ↫ Suzanne Frey at the Android Developer Blog

This new policy will only apply to “certified Android devices”, which means Android devices that ship with Google Play Services and all related Google stuff preinstalled. How this policy will affect devices running de-Googled Android ROMs like GrapheneOS where the user has opted to install the Play Store and Google Play Services is unclear. Google does claim the personal information you hand over as part of your registration will remain entirely private and not be shown to anyone, but that’s not going to reassure anyone.

To its small credit, Google intends to create an Android Developer Console explicitly for developers who only operate outside of the Play Store, and a special workflow for students and hobbyists that waives the $25 fee. First tests will start in October of this year, with an official rollout in a number of countries later in 2026, which will then expand to cover the whole world. The first countries seeing the official rollout will be countries hit especially hard by scams (according to Google’s research, at least): Brazil, Indonesia, Singapore, and Thailand.

Google has been trying to claw back control over Android for years now, and it seems the pace is accelerating lately. None of these steps should surprise you, but they should highlight just how crucially important it is that we somehow managed to come to a viable third way, something not controlled by either Apple or Google.
                </pre>
            </li>
            @if ($event['attendance'])
            <li>
                <h3>ATTENDANCE</h3>
                @switch ($event['event']->participant_type)
                @case ('students')
                    @if ($event['attendanceTotal'] <= 15)
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
                    @else
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
                    @endif
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
            </li>
            @endif
            {{--
            <li>
                <h3>EVALUATION</h3>
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
            <li class="attachment-section">
                <h3>ATTACHMENTS</h3>
                @php $skip = false; @endphp
                @foreach ($event['event']->attachmentSets()->orderBy('created_at', 'asc')->get() as $set)
                    @foreach ($set->attachments()->orderBy('created_at', 'asc')->get() as $i => $attachment)
                        @if ($skip)
                            @php $skip = false; @endphp
                            @continue;
                        @endif
                        @php $next = $set->attachments[$i + 1] ?? null; @endphp
                <div class="attachment">
                    <img class="{{ $attachment->orientation }} {{ $attachment->full_width ? 'full-width' : null }}" src="app/private/{{ $attachment->image_filepath }}">
                        @if (!($attachment->standalone || $attachment->full_width) && in_array($attachment->orientation, ['portrait', 'square']) && $next && in_array($next->orientation, ['portrait', 'square']) && !($next->standalone || $next->full_width))
                    <img class="{{ $next->orientation }}" src="app/private/{{ $next->image_filepath }}">
                            @php $skip = true; @endphp
                        @endif
                </div>
                    @endforeach
                <p class="caption">{{ $set->caption }}</p>
                @endforeach
            </li>
        </ol>
    </main>
    @endforeach
</body>
</html>