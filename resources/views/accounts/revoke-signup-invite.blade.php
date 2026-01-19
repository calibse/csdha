<x-layout.user form :$backRoute class="accounts form" title="Revoke Sign up Invitation">
<div class="article">
    <p>
        Are you sure you want to revoke this sign up invitation for <strong>{{ $invite->position?->name }}</strong>?
    </p> 
    <div class="submit-buttons">
        <button form="cancel-form">Cancel</button>
        <button form="delete-form">Revoke</button>
    </div>
    <form id="cancel-form" action="{{ $backRoute }}"></form>
    <form id="delete-form" method="post" action="{{ $formAction }}"> 
        @method('DELETE') 
        @csrf 
    </form>
</div>
</x-layout.user>