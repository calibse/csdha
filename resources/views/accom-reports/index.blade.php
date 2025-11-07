<x-layout.user index title="Accomplishment Reports" class="gpoa index">
    <x-slot:toolbar>
    @if ($gpoa)
        <a href="{{ $genRoute }}">
		<img class="icon" src="{{ asset('icon/light/file-plus-duotone.png') }}">

            <span class="text">Gen. PDF</span>
        </a>
        <a
        @can ('updateAccomReportBG', 'App\Models\Event')
                href="{{ $changeBgRoute }}"
        @endcan
        >
                <img class="icon" src="{{ asset('icon/light/pencil-simple-duoton
e.png') }}">
                <span class="text">Change Background</span>
        </a>
    @endif
    </x-slot:toolbar>
    <article class="article">
        <x-alert/>
        @if ($accomReports->isNotEmpty())
        <table class="table-2">
            <colgroup>
                <col style="width: 30%">
                <col style="width: 70%">
            </colgroup>
            <thead>
                <tr>
                    <th>Event name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($accomReports as $accomReport)
                <tr>
                    <td class="activity-name">
                        <a href="{{ route('accom-reports.show', ['event' => $accomReport->event->public_id]) }}">
                            {{ $accomReport->event->gpoaActivity->name }}
                        </a>
                    </td>
                    <td>{{ $accomReport->full_status }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--
        <ul class="item-list-icon">
        @foreach ($accomReports as $accomReport)
            <li class="item">
                <div class="icon">
                    <x-phosphor-file-text/>
                </div>
                <div class="content">
                    <p class="title">
                        <a href="{{ route('accom-reports.show', ['event' => $accomReport->event->public_id]) }}">
                            {{ mb_strimwidth($accomReport->event->gpoaActivity->name, 0, 70, '...') }}
                        </a>
                    </p>
                    <p class="subtitle">Status: {{ mb_strimwidth(ucwords($accomReport->status), 0, 70, '...') }} </p>
                </div>
            </li>
        @endforeach
        </ul>
        --}}
        {{ $accomReports->links('paginator.simple') }}
        @elseif (!$gpoa)
		<p>There is no active GPOA right now.</p>
        @else
            @switch (auth()->user()->position_name)
            @case('president')
        <p>No one has submitted anything yet.</p>
                @break
            @default
        <p>No one has added anything yet.</p>
            @endswitch
        @endif
    </article>
</x-layout.user>
