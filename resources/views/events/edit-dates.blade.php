@php
$routeParams = ['event' => $event->public_id]
@endphp
<x-layout.user route="events.edit" :$routeParams class="events" title="Edit Dates">
    <x-slot:toolbar>
        <a href="{{ route('events.dates.create', ['event' => $event->public_id]) }}">
            <span class="icon"><x-phosphor-plus-circle/></span>
            <span class="text">Add Date</span>
        </a>
    </x-slot:toolbar>
    <article class="article">
        <x-alert/>
        <ul class="item-list">
        @foreach ($event->dates as $date)
            <li class="item event-date">
                <time class="content">{{ $date->full_date }}</time>
                <span class="context-menu">
                    <form action="{{ route('events.dates.edit', ['event' => $event->public_id, 'date' => $date->public_id]) }}" class="edit-action">
                        <button type="submit"
                            @cannot ('update', $date)
                            disabled
                            @endcannot
                        >Edit</button>
                        <script type="application/json" class="field-values">
{
    "date": "{{ $date->date }}",
    "startTime": "{{ $date->start_time }}",
    "endTime": "{{ $date->end_time }}" 
}
                        </script>
                    </form>
                    <form action="{{ route('events.dates.confirmDestroy', ['event' => $event->public_id, 'date' => $date->public_id]) }}" class="delete-action">
                        <button type="submit">Delete</button>
                    </form>
                </span>
            </li>
            @endforeach
        </ul>
    </article>
    <dialog popover id="add-date">
        <form method="POST" action="{{ route('events.dates.store', ['event' => $event->public_id, ], false) }}">
        @csrf
            <p class="datetime-range">
                <label>Date</label>
                <span>
                    <input type="date" required name="date">
                    <span>
                        <label>Start time</label>
                        <input type="time" required name="start_time">
                    </span>
                    <span>
                        <label>End time</label>
                        <input type="time" required name="end_time">
                    </span>
                </span>
            </p>
            <p class="form-submit">
                <button type="button" popovertarget="add-date">Cancel</button>
                <button type="submit">Save</button>
            </p>
        </form>
    </dialog>
    <dialog id="edit-event-date-dialog">
        <form method="POST">
            @method('PUT')
            @csrf
            <p class="datetime-range">
                <label>Date</label>
                <span>
                    <input type="date" required name="date">
                    <span>
                        <label>Start time</label>
                        <input type="time" required name="start_time">
                    </span>
                    <span>
                        <label>End time</label>
                        <input type="time" required name="end_time">
                    </span>
                </span>
            </p>
            <p class="form-submit">
                <button formnovalidate formmethod="dialog">Cancel</button>
                <button type="submit">Update</button>
            </p>
        </form>
    </dialog>
    <dialog id="delete-event-date-dialog">
        <form method="POST">
            @method('DELETE')
            @csrf
            <p>
                <span>Are you sure you want to delete this 
                date</span>
                <strong><time class="content-delete"></time></strong>?
            </p>
            <p class="form-submit">
                <button formnovalidate formmethod="dialog">Cancel</button>
                <button type="submit">Delete</button>
            </p>
        </form>
    </dialog>
</x-layout.user>