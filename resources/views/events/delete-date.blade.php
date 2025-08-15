@php
$routeParams = ['event' => $event->public_id];
@endphp
<x-layout.user route="events.dates.index" :$routeParams class="events form" title="Delete Date">
    <article class="article">
        <p>Are you sure you want to delete this date 
            <strong><time>{{ $date->full_date }}</time></strong>?
        </p> 
        <div class="submit-buttons">
            <form action="{{ route('events.dates.index', $routeParams) }}">
                <button>Cancel</button>
            </form>
            <form method="POST" action="{{ route('events.dates.destroy', ['event' => $event->public_id, 'date' => $date->public_id]) }}"> 
                @method('DELETE')
                @csrf
                <button>Delete</button>
            </form>
        </div>
    </article>
</x-layout.user>