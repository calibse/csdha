<x-layout.user title="Create Sign-up Invite" route="accounts.index" class="accounts form">
    <article class="article">
    @if (session('sent'))
        <p class="form-status">
            Sign-up invitation sent.
        </p>
    @elseif (session('deleted'))
        <p class="form-status">
            Sign-up invitation revoked.
        </p>
    @endif
        <form type="POST" action="{{ route('accounts.sendSignupInvite', [], false) }}">
            <p>
                <label>Council Body Position</label>
                <select required name="position">
                    <option value="">-- Select position --</option>
                    <option value="0">No position</option>
            @foreach ($positions as $position)
                @if (!$position->user 
                    && !$position->signupInvitations()->firstWhere('is_accepted', 0))
                    <option value="{{ $position->id }}">
                        {{ $position->name }}
                    </option>
                @endif
            @endforeach
                </select>
            </p>
            <p>
                <label>Email address</label>
                <input type="email" name="email" required>
            </p>
            <p class="form-submit">
                <button type="submit">Send</button>
            </p>
        </form>
        <section class="list">
            <h2 class="title">Pending Sign-up Invitations</h2>
    @foreach ($invites as $invite)
        @if($invite->is_accepted === 0)
            <section class="item">
                <h3 class="title">{{ $invite->position?->name ?? 'No position'}}</h3>
                <p>{{ $invite->email }}</p>
                <p>Status: {{ $invite->is_accepted ? 'Accepted' : 'Pending' }}</p>
                <p>
                    <button type="button" popovertarget="invite-{{ $invite->id }}">
                        Revoke
                    </button>
                </p>
            </section>
        </section>
        <dialog popover id="invite-{{ $invite->id }}">
            <form method="POST" action="{{ route('accounts.revokeSignupInvite', ['invite' => $invite->id ]) }}" >
                @csrf
                @method('DELETE')
                <p>
                    Are you sure you want to revoke sign-up invitation for 
                    <strong>{{ $invite->position?->name ?? 'none'}}</strong> position?
                </p>
                <p>
                    <button type="button" popovertarget="invite-{{ $invite->id }}">
                        Cancel
                    </button>
                    <button type="submit">Revoke</button>
                </p>
            </form>
        </dialog>
        @endif
    @endforeach
    </article>
</x-layout.user>