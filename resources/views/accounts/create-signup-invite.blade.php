<x-layout.user title="Create Sign-up Invite" :$backRoute class="accounts form">
    <article class="article">
        <x-alert/>
        <form type="post" action="{{ $formAction }}">
            <p>
                <label>Council Body Position</label>
                <select name="position">
                    <option value="">-- Select position --</option>
                    <option value="0" {{ old('position') === '0' ? 'selected' : null }}>
                        No position
                    </option>
                    @foreach ($positions as $position)
                    <option value="{{ $position->id }}" {{ old('position') === (string) $position->id ? 'selected' : null }}>
                        {{ $position->name }}
                    </option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>Email address</label>
                <input type="email" name="email" value="{{ old('email') }}">
            </p>
            <p class="form-submit">
                <button >Send</button>
            </p>
        </form>
        @if ($invites->isNotEmpty())
        <section>
            <h2 class="title">Pending Sign-up Invitations</h2>
            <ul class="item-list">
            @foreach ($invites as $invite)
                <li class="item">
                    <div class="content">
                        <p>{{ $invite->position?->name ?? 'No position'}}</p>
                        <p>{{ $invite->email }}</p>
                    </div>
                    <div class="context-menu">
                        <form action="{{ route('accounts.confirm-revoke-signup-invite', ['invite' => $invite->id]) }}">
                            <p>
                                <button>Revoke</button>
                            </p>
                        </form>
                    </div>
                </li>
            @endforeach
            </ul>
        </section>
        @endif
    </article>
</x-layout.user>