@php
$routeParams = [
    'activity' => $activity->public_id
]; 
@endphp
<x-layout.user route="gpoa.activities.show" :$routeParams class="form gpoa" title="Delete GPOA Activity">
    <article class="article">
        <p>Are you sure you want to delete GPOA activity 
            "{{ $activity->name }}"?
        </p> 
        <div class="submit-buttons">
            <form action="{{ route('gpoa.activities.show', $routeParams) }}">
                <button>Cancel</button>
            </form>
            <form method="post" action="{{ route('gpoa.activities.destroy', $routeParams) }}">
                @csrf
                @method('DELETE')
                <button>Delete</button>
            </form>
        </div>
    </article>
</x-layout.user>