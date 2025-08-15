@php
$routeParams = ['gspoa' => $gspoa->id]
@endphp
<x-layout.user route="gspoas.show" :$routeParams class="gspoas" title="Edit Events">
    <nav class="main-actions of-item">
        <button popovertarget="add-event">
            <span class="icon"><x-phosphor-plus-circle/></span>
            <span class="text">Add Event</span>
        </button>
    </nav>
    <article class="article">
    @if (session('saved'))
        <aside class="form-status">
            Event added.
        </aside>
    @endif
    @if (session('deleted'))
        <aside class="form-status">
            Event deleted.
        </aside>
    @endif
    @if (session('updated'))
        <aside class="form-status">
            Event updated.
        </aside>
    @endif
        <ul class="item-list">
        @foreach ($gspoa->events as $event)
            <li class="item gspoa-event">
                <span class="content">{{ $event->title }}</span>
                <span class="context-menu">
                    <form action="{{ route('gspoas.events.edit', ['gspoa' => $gspoa->id, 'event' => $event->id]) }}" class="edit-action">
                        <button onclick="event.preventDefault()" type="submit">Edit</button>
                        <script type="application/json" class="field-values">
{
    "title": "{{ $event->title }}",
    "participants": "{{ $event->participants }}",
    "venue": "{{ $event->venue }}",
    "objective": "{{ $event->objective }}"
}
                        </script>
                    </form>
                    <form action="{{ route('gspoas.events.confirmDelete', ['gspoa' => $gspoa->id, 'event' => $event->id]) }}" class="delete-action">
                        <button onclick="event.preventDefault()" type="submit">Delete</button>
                    </form>
                </span>
            </li>
        @endforeach
        </ul>
    </article>
    <dialog popover id="add-event">
        <form method="POST" action="{{ route('gspoas.events.store', ['gspoa' => $gspoa->id]) }}">
        <form>
            @csrf
            <p>
                <label>Title</label>
                <input name="title" required>
            </p>
            <p>
                <label>Participants description</label>
                <input name="participants" required>
            </p>
            <p>
                <label>Participants Student Years</label>
                <select multiple required name="years[]">
            @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->year }}</option>
            @endforeach
                </select>
            </p>
            <p>
                <label>Venue</label>
                <input name="venue" required>
            </p>
            <p>
                <label>Objective</label>
                <textarea name="objective" required></textarea>
            </p>
            <p class="form-submit">
                <button type="button" popovertarget="add-event">Cancel</button>
                <button type="submit">Save</button>
            </p>
        </form>
    </dialog>
    <dialog id="edit-gspoa-event-dialog">
        <form method="POST">
            @method('PUT')
            @csrf
            <p>
                <label>Title</label>
                <input name="title" required>
            </p>
            <p>
                <label>Participants description</label>
                <input name="participants" required>
            </p>
            <p>
                <label>Participants Student Years</label>
                <select multiple required name="years[]">
            @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->year }}</option>
            @endforeach
                </select>
            </p>
            <p>
                <label>Venue</label>
                <input name="venue" required>
            </p>
            <p>
                <label>Objective</label>
                <textarea name="objective" required></textarea>
            </p>
            <p class="form-submit">
                <button formnovalidate formmethod="dialog">Cancel</button>
                <button type="submit">Update</button>
            </p>
        </form>
    </dialog>
    <dialog id="delete-gspoa-event-dialog">
        <form method="POST">
            @method('DELETE')
            @csrf
            <p>
                <span>Are you sure you want to delete this</span>
                <strong><span class="content-delete"></span></strong> event?
            </p>
            <p class="form-submit">
                <button formnovalidate formmethod="dialog">Cancel</button>
                <button type="submit">Delete</button>
            </p>
        </form>
    </dialog>
</x-layout.user>