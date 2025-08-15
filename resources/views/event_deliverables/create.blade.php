@php
$routeParams = ['event' => $event->id];
@endphp
<x-layout.user route="events.show" 
    :$routeParams 
    class="events" 
    title="Create a Deliverable">
    <form method="POST"
        action="{{ route('events.deliverables.store', ['event' => $event->id], false) }}">
        @csrf
        <p>
            <label>Deliverable Name</label>
            <input name="name" type="text">
        </p>
        <p>
            <label>Assignees</label>
            <select multiple 
                name="assignees[]" 
                size="5"
            >
                @foreach ($users as $user)
                    @unless ($user->hasEventDeliverables($event))
                <option value="{{ $user->id }}">{{ $user->fullName }}</option>
                    @endunless
                @endforeach
            </select>
        </p>
        <p>
            <button type="submit">Create</button>
        </p>
    </form>
</x-layout.user>