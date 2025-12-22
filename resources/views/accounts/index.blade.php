<x-layout.user has-toolbar index class="accounts index" title="Accounts">
    <x-slot:toolbar>
        <a href="{{ route('accounts.create-signup-invite') }}">
		<img class="icon" src="{{ asset('icon/light/plus.png') }}">
            <span class="text">Create Sign-up Invite</span>
        </a>
    </x-slot:toolbar>
    <div class="article">
        <x-alert/>
        <table class="main-table table-4">
            <colgroup>
                <col span="4" style="width: 25%">
            </colgroup>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                <tr>
                    <td><a href="{{ route('accounts.show', ['account' => $account->public_id]) }}">{{ $account->full_name }}</a></td>
                    <td>{{ $account->email ?? '(None)' }}</td>
                    <td>{{ $account->position?->name ?? '(None)' }}</td>
                    <td>{{ $account->role?->name ?? '(None)' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table> 
        {{ $accounts->links('paginator.simple') }}
    </div>
</x-layout.user>
