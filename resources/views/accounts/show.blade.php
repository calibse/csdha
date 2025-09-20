<x-layout.user title="Account" :$backRoute class="accounts form">
    <article class="article">
        <x-alert/>
        <form method="post" action="{{ $formAction }}">
            @csrf
            @method('PUT')
            <p>
                <label>Position</label>
                <input disabled value="{{ $account->position?->name }}">
            </p>
            <p>
                <label>Role</label>
                <input disabled value="{{ $account->role?->name }}">
            </p>
            <p>
                <label>Email</label>
                <input disabled value="{{ $account->email }}">
            </p>
            <p>
                <label>Username</label>
                <input disabled value="{{ $account->username }}" >
            </p>
            <p>
                <label>First name</label>
                <input name="first_name" value="{{ $account->first_name }}" >
            </p>
            <p>
                <label>Middle name</label>
                <input name="middle_name" value="{{ $account->middle_name }}" >
            </p>
            <p>
                <label>Last name</label>
                <input name="last_name" value="{{ $account->last_name }}" >
            </p>
            <p>
                <label>Suffix name</label>
                <input name="suffix_name" value="{{ $account->suffix_name }}" >
            </p>
            <p class="form-submit">
                <button>Update</button>
                <button form="form-deactivate"
                @cannot ('delete', $account)
                    disabled
                @endcan
                >Delete</button>
            </p>
        </form>
        <form id="form-deactivate" action="{{ route('accounts.confirm-destroy', ['account' => $account->public_id]) }}"> </form>
    </article>
</x-layout.user>
