<x-layout.user index title="Roles" class="events index form">
    <article class="article">
        <x-alert/>
        <form method="post" action={{ $formAction }} >
            @method('PUT')
            @csrf
            @foreach ($roles as $role)
            <p>
                <label>{{ ucwords($role->name) }}</label>
                <select multiple required size="5" name="roles[{{ $role->id }}][]">
                    @foreach ($users as $user)
                    <option value="{{ $user->public_id }}" {{ $role->users()->whereKey($user->id)->exists() ? 'selected' : null }}>
                        {{ $user->fullName }}
                    </option>
                    @endforeach
                </select>
            </p>
            @endforeach
            <p class="form-submit">
                <button type="submit"
                >Update</button>
            </p>
        </form>
    </article>
</x-layout.user>