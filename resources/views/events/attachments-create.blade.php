<x-layout.user class="events attachments form create" :$backRoute title="{{ $set ? 'Update' : 'Create' }} Attachment Set">
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
                <input id="images-input" name="images[]" type="file" accept="image/jpeg, image/png" multiple>
            </p>
            <figure id="attachment-view-links" class="attachment-view-links">
            </figure>
            <template id="attachment-view-link-temp">
                <a class="view-link"><img></a>
            </template>
            <p class="form-submit">
                <button>{{ $set ? 'Update' : 'Create' }}</button>
                @if ($set)
                <button form="delete-form">Delete Set</button>
                @endif
            </p>
        </form>
        <div id="attachment-views" class="attachment-views">
        </div>
        <template id="attachment-view-temp">
            <div class="view">
                <ul class="toolbar">
                    <li>
                        <a href="#" class="close-button">
                            <span class="icon"><x-phosphor-arrow-left/></span>
                            <span class="text">Close Viewer</span>
                        </a>
                    </li>
                    <li>
                        <button class="remove-button">
                            <span class="icon"><x-phosphor-minus-circle/></span>
                            <span class="text">Remove file</span>
                        </button>
                    </li>
                </ul>
                <figure class="file">
                    <img>
                </figure>
            </div>
        </template>
        @if ($set)
        <form id="delete-form" action="{{ $deleteRoute }}"></form>
        @endif
    </article>
</x-layout.user>
