<x-layout.user :$backRoute title="Accomplishment Report" class="event">
    <x-slot:toolbar>
        @if ($actions['submit'])
        <a 
        @can('submitAccomReport', $event)
            href="{{ $submitRoute }}"
        @endcan
        >
            <span class="icon"><x-phosphor-arrow-fat-line-right/></span> 
            <span class="text">Submit</span>
        </a>
        @endif
        @if ($actions['return'])
        <a 
        @can('returnAccomReport', $event)
            href="{{ $returnRoute }}"
        @endcan
        >
            <span class="icon"><x-phosphor-arrow-fat-line-left/></span> 
            <span class="text">Return</span>
        </a>
        @endif
        @if ($actions['approve'])
        <a 
        @can('approveAccomReport', $event)
            href="{{ $approveRoute }}"
        @endcan
        >
            <span class="icon"><x-phosphor-check-circle/></span> 
            <span class="text">Approve</span>
        </a>
        @endif
    </x-slot:toolbar>
    <article class="article">
        <x-alert/>
        @if ($accomReport?->status)
        <p>Status: {{ ucwords($accomReport?->status) }}</p>
        @endif
        @if ($date)
        <p>Date: {{ $date }}</p>
        @endif
        @if ($accomReport?->comments)
        <p>Comments</p>
        <pre>{{ $accomReport?->comments }}</pre>
        @endif
        <figure class="pdf-document">
            <div class="pdf-file">
                <object data="{{ $fileRoute }}" type="application/pdf">
                    <p>
                        Preview of this file is unsupported. You may download
                        this file <a href="{{ $fileRoute }}">here</a>.
                    </p>
                </object>
            </div>
            <figcaption class="caption">Accomplishment Report</figcaption>
        </figure>
    </article>
</x-layout.user>
