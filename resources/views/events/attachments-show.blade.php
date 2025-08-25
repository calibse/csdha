<x-layout.user class="events form" :$backRoute title="Attachment">
    <article class="article">
        <figure class="image-file">
            <img src="{{ $fileRoute }}">
        </figure>
        <p class="submit-buttons">
            <button form="delete-form">Delete attachment</button>
        </p>
        <form id="delete-form" action="{{ $deleteRoute }}"></form>
    </article>
</x-layout.user>
