<x-layout.user index class="accounts index" title="Accounts">
    <x-slot:toolbar>
        <a href="{{ route('accounts.create-signup-invite') }}">
            <span class="icon"><x-phosphor-plus-circle/></span> 
            <span class="text">Create Sign-up Invite</span>
        </a>
    </x-slot:toolbar>
    <article class="table-block">
        <x-alert/>
        <table>
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
    </article>
</x-layout.user>