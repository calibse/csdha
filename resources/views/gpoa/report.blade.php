<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Test</title>
    {{-- 
    @vite(['resources/scss/gpoa-report.scss'])
    --}} 
    <style>{!! Vite::content('resources/scss/gpoa-report.scss') !!}</style>
</head>
<body>
    <article id="page-header">
        <div class="logos">
            <div class="logo">
                <img src="resources/pdf/images/pup-logo.svg"> 
            </div>
            <div class="logo">
                <img src="resources/pdf/images/cs-logo.png">
            </div>
        </div>
        <div class="text">
            <p class="country">Republic of the Philippines</p>
            <p class="school">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</p>
            <p class="campus">TAGUIG CITY CAMPUS</p>
            <p class="org-name">COMPUTER SOCIETY</p>
        </div>
    </article>
    <header class="main-header">
        <p>GENERAL PLAN OF ACTIVITIES</p>
        <p>{{ $gpoa->academicPeriod?->term?->label }} 
            A.Y. {{ $gpoa->academicPeriod?->year_label }}
        </p>
        <p>PUP-TAGUIG COMPUTER SOCIETY</p>
    </header>
    <table>
        <colgroup>
            <col style="width: 1.3cm"><!-- Number -->
            <col style="width: 3cm"><!-- Name -->
            <col style="width: 2cm"><!-- Date -->
            <col style="width: 5cm"><!-- Objectives -->
            <col style="width: 3cm"><!-- Participants -->
            <col style="width: 3cm"><!-- Type -->
            <col style="width: 2cm"><!-- Partnership -->
            <col style="width: 2cm"><!-- Budget -->
            <col style="width: 2cm"><!-- Fund -->
            <col style="width: 2cm"><!-- Mode -->
            <col style="width: 2.5cm"><!-- Event Head -->
        </colgroup>
        <thead>
            <tr>
                <th>No.</th>
                <th>Name of Activity</th>
                <th>Date</th>
                <th>Objectives</th>
                <th>Participant(s)/Beneficiary(ies) (indicate the number)</th>
                <th>Type of Activity</th>
                <th>Partnership</th>
                <th>Proposed Budget</th>
                <th>Source of Fund</th>
                <th>Mode</th>
                <th>Event Head</th>
            </tr>
        </thead>
        <tbody>
        @php
$actCount = 0;
        @endphp
        @foreach($activities as $activity)
            <tr>
                <td>{{ ++$actCount . '.' }}</td>
                <td>{{ $activity->name }}</td>
                <td>{{ $activity->date }}</td>
                <td>{{ $activity->objectives }}</td>
                <td>{{ $activity->participants }}</td>
                <td>{{ $activity->type?->name }}</td>
                <td>{{ $activity->partnershipType?->name }}</td>
                <td>{{ $activity->proposed_budget }}</td>
                <td>{{ $activity->fundSource?->name }}</td>
                <td>{{ $activity->mode?->name }}</td>
                <td>
                    <ul>
                    @foreach ($activity->eventHeadsOnly as $eventHead)
                        <li>{{ $eventHead->full_name }}</li>
                    @endforeach
                    </ul>
                @php
$coheads = $activity->coheads
                @endphp
                @if ($coheads->isNotEmpty())
                    <p>Co-head:</p>
                    <ul>
                    @foreach ($coheads as $cohead)
                        <li>{{ $cohead->full_name }}</li>
                    @endforeach
                    </ul>
                @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <section class="signatures-page">
        <div class="column">
            <p>Prepared by:</p>
            <ul class="person-list">
                <li>
                    <div class="signature"></div>
                    <p class="name">{{ auth()->user()->full_name }}</p>
                    <p class="position">{{ auth()->user()->position?->name }}, 
                        PUP-Taguig Computer Society
                    </p>
                </li>
                <li>
                    <div class="signature"></div>
                    <p class="name">{{ $president?->full_name }}</p>
                    <p class="position">{{ $president?->position?->name }},
                        PUP-Taguig Computer Society
                    </p>
                </li>
            </ul>
            <p>Noted by:</p>
            <ul class="person-list">
                <li>
                    <div class="signature"></div>
                    <p class="name">{{ $adviser?->full_name }}</p>
                    <p class="position">{{ $adviser?->position?->name }}, 
                        PUP-Taguig Computer Society
                    </p>
                </li>
            </ul>
        </div>
        <div class="column">
            <p>Approved by:</p>
            <ul class="person-list">
                <li>
                    <div class="signature"></div>
                    <p class="name">Asst. Prof. Bernadette L. Canlas</p>
                    <p class="position">Head of Student Services</p>
                </li>
                <li>
                    <div class="signature"></div>
                    <p class="name">Dr. Marissa B. Ferrer</p>
                    <p class="position">PUP-Taguig Branch Director</p>
                </li>
            </ul>
        </div>
    </section>
</body>
</html>
