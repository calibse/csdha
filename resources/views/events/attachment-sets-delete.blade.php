<x-layout.user :$backRoute class="events form" title="Delete Attachment Set">
    <article class="article">
        <p>
            Are you sure you want to delete this attachment set 
            "<strong>{{ $set->caption }}</strong>"?
        </p> 
        <div class="submit-buttons">
            <button form="cancel-form">Cancel</button>
            <button form="delete-form">Delete</button>
        </div>
        <form id="cancel-form" action="{{ $backRoute }}"></form>
        <form id="delete-form" method="post" action="{{ $formAction }}"> 
            @method('DELETE') 
            @csrf 
        </form>
    </article>
</x-layout.user>