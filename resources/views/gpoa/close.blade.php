<x-layout.user route="gpoa.index" class="gpoa form" title="Close GPOA">
    <article class="article">
        <p>Are you sure you want to close this GPOA for 
           {{ $gpoa->academicPeriod->term->label }} 
           A.Y. {{ $gpoa->academicPeriod->year_label }}?
        </p> 
        <div class="submit-buttons">
            <form action="{{ route('gpoa.index') }}">
                <button>Cancel</button>
            </form>
            <form method="post" action="{{ route('gpoa.close') }}">
                @csrf
                @method('PUT')
                <button>Close GPOA</button>
            </form>
        </div>
    </article>
</x-layout.user>