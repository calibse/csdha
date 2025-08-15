<x-layout.user route="gspoas.index" class="gspoas" title="General Strategic Plan of Activities">
    <nav class="main-actions of-item">
    @if ($actions['edit'])
        <a 
        @can ('update', $gspoa)
            href="{{ route('gspoas.edit', ['gspoa' => $gspoa->id]) }}
        @endcan
        ">
            <span class="icon"><x-phosphor-pencil-simple/></span> 
            <span class="text">Edit </span>
        </a>
    @endif
    @if ($actions['edit'])
        <a 
        @can ('update', $gspoa)
            href="{{ route('gspoas.events.index', ['gspoa' => $gspoa->id]) }}
        @endcan
        ">
            <span class="icon"><x-phosphor-pencil-simple/></span> 
            <span class="text">Edit Events</span>
        </a>
    @endif
    @if ($actions['return'])
        <a 
        @can ('return', $gspoa)
            href="{{ route('gspoas.prepareForReturn', ['gspoa' => $gspoa->id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-arrow-fat-line-left/></span> 
            <span class="text">Return</span>
        </a>
    @endif
    @if ($actions['reject'])
        <a 
        @can ('reject', $gspoa)
            href="{{ route('gspoas.prepareForReject', ['gspoa' => $gspoa->id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-x-circle/></span> 
            <span class="text">Reject</span>
        </a>
    @endif
    @if ($actions['submit'])
        <a 
        @can ('submit', $gspoa)
            href="{{ route('gspoas.prepareForSubmit', ['gspoa' => $gspoa->id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-arrow-fat-line-right/></span> 
            <span class="text">Submit</span>
        </a>
    @endif
    @if ($actions['approve'])
        <a 
        @can ('approve', $gspoa)
            href="{{ route('gspoas.prepareForApprove', ['gspoa' => $gspoa->id]) }}"
        @endcan
        >
            <span class="icon"><x-phosphor-check-circle/></span> 
            <span class="text">Approve</span>
        </a>
    @endif
    </nav>
    <article class="article document">
        <aside>
        @if ($gspoa->comments)
            <header>
                <p><strong>{{ $gspoa->comment_purpose }}</strong></p>
            </header>
            <pre>{{ $gspoa->comments }}</pre> 
        @endif
            <p>Status: {{ $gspoa->current_status }}</p>
        </aside>
        <hr>
        {{--
        <h2>{{ $gspoa->program_title }}</h2>

        <h3>Executive Summary</h3>
        <pre>{{ $gspoa->executive_summary }}</pre>

        <h3>Objectives</h3>
        <pre>{{ $gspoa->objectives }}</pre>
        --}}

        <h2>Events</h2>
        <div class="table-block">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Participants</th>
                        <th>Venue</th>
                        <th>Objective</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($gspoa->events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->participants }}</td>
                        <td>{{ $event->venue }}</td>
                        <td>{{ $event->objective }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    {{--
    @foreach ($gspoa->events as $event)
        <h4>{{ $event->title }}</h4>
        <p>Participants: {{ $event->participants }}</p>
        <p>Venue: {{ $event->venue }}</p>
        <p>Objective:</p> 
        <pre>{{ $event->objective }}</pre>
    @endforeach
    --}}

        {{--
        <h3>Marketing and Promotion</h3>
        <pre>{{ $gspoa->promotion }}</pre>

        <h3>Logistics</h3>
        <pre>{{ $gspoa->logistics }}</pre>

        <h3>Financial Plan</h3>
        <pre>{{ $gspoa->financial_plan }}</pre>

        <h3>Safety and Security</h3>
        <pre>{{ $gspoa->safety }}</pre>
        --}}
    </article>
</x-layout.user>

