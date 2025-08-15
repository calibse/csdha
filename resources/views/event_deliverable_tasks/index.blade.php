@php
$routeParams = [
    'event' => $event->id,
    'deliverable' => $deliverable->id
];
@endphp
<x-layout.user route="events.deliverables.show"
    :$routeParams
    class="events"
    title="Edit Deliverable Tasks"
>
@if (session('saved'))
<aside class="form-status">
    Changes saved
</aside>
@endif
<ul class="item-list">
    @foreach($deliverable->tasks as $task)
    <li class="item">
        <span class="content">
            {{ $task->name }}
        </span>
        <section id="context-menu-{{ $task->id }}" 
            class="context-menu"
        >
            <button type="button"
                popovertarget="edit-task-{{ $task->id }}"
            >
                Edit
            </button>
            <button type="button"
                popovertarget="delete-task-{{ $task->id }}"
            >
                Delete
            </button>
        </section>
    </li>
    @endforeach
</ul>
@foreach ($deliverable->tasks as $task)
<dialog popover
    id="edit-task-{{ $task->id }}"
>
    <form method="POST"
        action="{{ route('events.deliverables.tasks.update', [
                'event' => $event->id,
                'deliverable' => $deliverable->id,
                'task' => $task->id
            ], false) }}"
    >
    @method('PUT')
    @csrf
        <p>
            <label>Task name</label>
            <input type="text"
                name="name"
                value="{{ $task->name }}"
            >
        </p>
        <p class="form-submit">
            <button type="button"
                popovertarget="edit-task-{{ $task->id }}"
            >
                Cancel
            </button>
            <button type="submit">Save</button>
        </p>
    </form>
</dialog>
<dialog popover id="delete-task-{{ $task->id }}">
    <form method="POST"
        action="{{ route('events.deliverables.tasks.update', [
                'event' => $event->id,
                'deliverable' => $deliverable->id,
                'task' => $task->id
            ], false) }}"
    >
    @method('DELETE')
    @csrf
        <p>
            Are you sure you want to delete the 
            <strong>{{ $task->name }}</strong> task?
        </p>
        <p class="form-submit">
            <button type="button"
                popovertarget="delete-task-{{ $task->id }}"
            >
                Cancel
            </button>
            <button type="submit">Delete</button>
        </p>
    </form>
</dialog>
@endforeach
</x-layout.user>