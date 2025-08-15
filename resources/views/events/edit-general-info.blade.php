@php
$routeParams = ['event' => $event->id]
@endphp
<x-layout.user class="events" 
    route="events.show" 
    :$routeParams 
    title="Edit General Information"
>
    <form method="POST" 
        action="{{ route('events.general.update', [
            'event' => $event->id
        ], false) }}" 
        enctype="multipart/form-data"
    >
    @method("PUT")
    @csrf
        <p>
            <label>Venue</label>
            <input  name="venue" 
                type="text"
                value="{{ $event->venue }}"
            >
        </p>
        <p>
            <label>Type of Activity</label>
            <input  name="type" 
                type="text"
                value="{{ $event->type_of_activity }}"
            >
        </p>
        <p>
            <label>Participants</label>
            <input  name="participants" 
                type="text"
                value="{{ $event->participants }}"
            >
        </p>
        <p>
            <label>Objective</label>
            <textarea  name="objective" 
            >{{ $event->objective }}</textarea>
        </p>
        <p>
            <label>Description</label>
            <textarea name="description"
            >{{ $event->description }}</textarea>
        </p>
        <p>
            <label>Narrative</label>
            <textarea class="input-narrative" 
                name="narrative"
            >{{ $event->narrative }}</textarea>
        </p>
        <p class="form-submit">
            <button type="submit">Update Event</button>
        </p>
    </form>
</x-layout.editing>
