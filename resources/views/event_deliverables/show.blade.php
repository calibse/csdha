@php
$routeParams = ['event' => $event->id];
@endphp
<x-layout.user title="Deliverable" 
    route="events.show"
    :$routeParams
    class="events"
>
    @if (session('saved'))
    <aside class="form-status">Changes saved.</aside>
    @endif
    <h2 class="content-title">{{ $deliverable->name }}</h2>
    <nav class="main-actions">
    @can('update', $event)
        <a href="{{ route('events.deliverables.edit', [
                'event' => $event->id,
                'deliverable' => $deliverable->id
            ], false) }}">
            <span class="icon">
                <x-phosphor-pencil-simple/>
            </span>
            <span class="text">
                Edit
            </span>
        </a>
    @endcan
    </nav>
    <section class="tasks">
        <h3 class="title">Tasks</h3>
        <nav class="main-actions">
        @can('update', $event)
            <button popovertarget="add-task" type="button">
                <span class="icon">
                    <x-phosphor-plus-circle/>
                </span>
                <span class="text">
                    Add Task
                </span>
            </button>
            <a href="{{ route('events.deliverables.tasks.index', [
                    'event' => $event->id,
                    'deliverable' => $deliverable->id
                ], false) }}">
                <span class="icon">
                    <x-phosphor-pencil-simple/>
                </span>
                <span class="text">
                    Edit Tasks
                </span>
            </a>
        @endcan
        </nav>
        <form method="POST" 
            action="{{ route('events.deliverables.updateTasks', [
                    'event' => $event->id,
                    'deliverable' => $deliverable->id
                ], false) }}"
        >
        @method('PUT')
        @csrf
            <ul class="item-list">
                @foreach($deliverable->tasks as $task)
                <li class="item">
                    <input type="checkbox"
                        @cannot('update', $deliverable)
                        disabled
                        @endcannot
                        id="task-{{ $task->id }}"
                        value="{{ $task->id }}"
                        {{ $task->is_done ? 'checked' : '' }}
                        name="tasks[]"
                    >
                    <label for="task-{{ $task->id }}">
                        {{ $task->name }}
                    </label>
                </li>
                @endforeach
            </ul>
            @can('update', $deliverable)
            <p>
                <button type="submit">Save</button>
            </p>
            @endcan
        </form>
    </section>
    <dialog popover id="add-task">
        <form method="POST"
            action="{{ route('events.deliverables.tasks.store', [
                'event' => $event->id,
                'deliverable' => $deliverable->id
            ], false) }}"
        >
        @csrf
            <p>
                <label>Task name</label>
                <input name="name" type="text">
            </p>
            <p class="form-submit">
                <button popovertarget="add-task" type="button">Cancel</button>
                <button type="submit">Add Task</button>
            </p>
        </form>
    </dialog>
</x-layout.user>