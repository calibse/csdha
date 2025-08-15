<x-layout.user title="Account" 
    route="accounts.index" 
    class="accounts"
>
    <form>
        <p>
            <label>First name</label>
            <input type="text"
                value="{{ $account->first_name }}"
            >
        </p>
        <p>
            <label>Middle name</label>
            <input type="text"
                value="{{ $account->middle_name }}"
            >
        </p>
        <p>
            <label>Last name</label>
            <input type="text"
                value="{{ $account->last_name }}"
            >
        </p>
        <p>
            <label>Suffix name</label>
            <input type="text"
                value="{{ $account->suffix_name }}"
            >
        </p>
        <p>
            <label>Email</label>
            <input type="email"
                value="{{ $account->email }}"
            >
        </p>
        <p>
            <button type="submit">
                Update
            </button>
            <button type="button">
                Delete
            </button>
        </p>
    </form>
</x-layout.user>