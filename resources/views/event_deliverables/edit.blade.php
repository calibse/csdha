@php
$routeParams = [
    'event' => $event->id,
    'deliverable' => $deliverable->id
];
@endphp
<x-layout.user route="events.deliverables.show" 
    :$routeParams 
    class="events" 
    title="Edit Deliverable">
    <form method="POST"
        action="{{ route('events.deliverables.update', [
                'event' => $event->id,
                'deliverable' => $deliverable->id
            ], false) }}">
        @method('PUT')
        @csrf
        <p>
            <label>Deliverable Name</label>
            <input name="name" 
                type="text"
                value="{{ $deliverable->name }}"
            >
        </p>
        <p>
            <label>{{ $deliverable->assignees->count() }} 
                {{ $deliverable->assignees->count() <= 1 ? 'Assignee' : 'Assignees' }}</label>
            <select multiple 
                name="assignees[]" 
                size="5"
            >
                @foreach ($deliverable->assignees as $assignee)
                <option selected
                    value="{{ $assignee->id }}"
                >
                    {{ $assignee->fullName }}
                </option>
                @endforeach
                @foreach ($users as $user)
                    @unless ($deliverable->assignees->contains($user)
                        || $user->hasEventDeliverables($event)
                    )
                <option value="{{ $user->id }}">
                    {{ $user->fullName }}
                </option>
                    @endunless
                @endforeach
            </select>
        </p>
        <p>
            <button type="submit">Save</button>
        </p>
    </form>
</x-layout.user>