<x-layout.user class="events form" :$backRoute title="{{ $set ? 'Update' : 'Create' }} Attachment Set">
    <article class="article">
        <form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
            @if ($set)
                @method('PUT')
            @endif
            @csrf
            <p>
                <label>Caption</label>
                <input name="caption" value="{{ old('caption') ?? ($set ? $set->caption : null) }}">
            </p>
            <p>
                <label>{{ $set ? 'Add' : null }} Images</label>
                <input name="images[]" type="file" accept="image/jpeg, image/png" multiple>
            </p>
            <p class="form-submit">
                <button>{{ $set ? 'Update' : 'Create' }}</button>
                @if ($set)
                <button form="delete-form">Delete Set</button>
                @endif
            </p>
        </form>
        @if ($set)
        <form id="delete-form" action="{{ $deleteRoute }}"></form>
        @endif
    </article>
</x-layout.user>
