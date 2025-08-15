@php
$routeParams = [
    'position' => $position->id
]; 
@endphp
<x-layout.user route="positions.show" :$routeParams class="form positions" title="Delete Position">
    <article class="article">
        <p>Are you sure you want to delete the <strong>{{ $position->name }}</strong> position?</p>
        <div class="submit-buttons">
            <form action="{{ route('positions.show', $routeParams) }}">
                <button>Cancel</button>
            </form>
            <form method="post" action="{{ route('positions.destroy', $routeParams) }}">
                @csrf
                @method('DELETE')
                <button>Delete</button>
            </form>
        </div>
    </article>
</x-layout.user>