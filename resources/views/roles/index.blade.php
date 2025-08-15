<x-layout.user index title="Roles" class="events index form">
    <article class="article">
    @if (session('saved'))
        <p class="form-status">
            Changes saved.
        </p>
    @endif
        <form method="POST"
            action={{ route('roles.update', [], false) }}
        >
            @method('PUT')
            @csrf
        @foreach ($roles as $role)
            <p>
                <label>{{ ucwords($role->name) }}</label>
                <select multiple required size="5" name="roles[{{ $role->id }}][]">
                    @foreach ($users as $user)
                        @if ($role->users()->find($user->id))
                    <option selected value="{{ $user->id }}">
                        {{ $user->fullName }}
                    </option>
                            @continue
                        @endif
                    <option value="{{ $user->id }}">
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