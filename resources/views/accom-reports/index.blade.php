<x-layout.user index title="Accomplishment Reports" class="gpoa index">
    <x-slot:toolbar>
        <a href="{{ $genRoute }}">
            <span class="icon"><x-phosphor-file-plus/></span> 
            <span class="text">Gen. PDF</span>
        </a>
    </x-slot:toolbar>
    <article class="article">
        <x-alert/>
        @if ($accomReports->isNotEmpty())
        <ul class="item-list-icon">
        @foreach ($accomReports as $accomReport)
            <li class="item">
                <div class="icon">
                    <x-phosphor-file-text/>
                </div>
                <div class="content">
                    <p class="title">
                        <a href="{{ route('accom-reports.show', ['event' => $accomReport->event->public_id]) }}">{{ $accomReport->event->gpoaActivity->name }}</a>
                    </p>
                    <p>Status: {{ ucwords($accomReport->status) }}</p>
                </div>
            </li>
        @endforeach
        </ul>
        {{ $accomReports->links('paginator.simple') }}
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
