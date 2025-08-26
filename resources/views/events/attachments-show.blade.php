<x-layout.user class="events form" :$backRoute title="Attachment">
    <article class="article">
        <figure class="image-file">
            <img src="{{ $fileRoute }}">
        </figure>
        <form method="post" action="{{ $updateRoute }}">
            @method('PUT')
            @csrf
            <fieldset>
                <legend>Layout</legend>
                <p class="checkbox-field">
                    <input id="full-width" name="full_width" type="checkbox" {{ $attachment->full_width ? 'checked' : null }}>
                    <label for="full-width">Full width</label>
                </p>
                <p class="checkbox-field">
                    <input id="standalone" name="standalone" type="checkbox" {{ $attachment->standalone ? 'checked' : null }}>
                    <label for="standalone">Standalone</label>
                </p>
            </fieldset>
            <p class="submit-buttons">
                <button>Update</button>
                <button form="delete-form">Delete attachment</button>
            </p>
        </form>
        <form id="delete-form" action="{{ $deleteRoute }}"></form>
    </article>
</x-layout.user>
