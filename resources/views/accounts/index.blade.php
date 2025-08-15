<x-layout.user index class="accounts index" title="Accounts">
    {{--
    <p class="main-action"><a href="{{ route('accounts.create', [], false) }}"><span class="icon"><x-icon.add/></span> Add account</a></p>
    --}}
    <x-slot:toolbar>
        <a href="{{ route('accounts.createSignupInvite') }}">
            <span class="icon"><x-phosphor-plus-circle/></span> 
            <span class="text">Create Sign-up Invite</span>
        </a>
    </x-slot:toolbar>

    {{--<article class="list">
        @foreach ($accounts as $account)
        <article class="item">
            <div class="icon">
                @if ($account->cover_photo_filepath)
                <img src="{{ route('events.showCoverPhoto', [
                    'account' => $account->id
                    ], false) }}">
                @else
                <x-icon.user/>
                @endif
            </div>
            <div class="info">
                <h2 class="title">
                    <a href="{{ route('accounts.show', [
                            'account' => $account->id
                        ], false) }}"
                    >
                    {{ $account->fullName }}
                    </a>
                </h2>
                <p>{{ $account->position?->name }}</p>
            </div>
        </article>
        @endforeach
    </article>
    {{ $accounts->links('paginator.simple') }}
    --}}

</x-layout.user>