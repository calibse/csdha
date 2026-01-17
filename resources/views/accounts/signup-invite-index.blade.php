<x-layout.user content-view title="Sign-up Invites" :$backRoute class="accounts signup-invitation form">
<x-slot:toolbar>
	<a 
		href="{{ $createRoute }}"
	>
		<img class="icon" src="{{ asset('icon/light/plus.png') }}">

		<span class="text">Create invite</span>
	</a>
</x-slot:toolbar>
<article class="article">
	<x-alert/>
@if ($invites->isNotEmpty())
	<ul class="item-list">
	@foreach ($invites as $invite)
		<li class="item">
			<div class="content">
				<p>{{ $invite->position?->name ?? 'No position'}}</p>
				<p>{{ $invite->email }}</p>
				<p>Status:
				@switch ($invite->email_sent)
				@case (1)
					Email sent
					@break
				@case (1)
					Email not sent
					@break
				@default
					Sending email
				@endswitch
				</p>
			</div>
			<div class="context-menu">
				<form action="{{ route('accounts.signup-invites.confirm-destroy', ['invite' => $invite->id]) }}">
					<button>Revoke</button>
				</form>
			</div>
		</li>
	@endforeach
	</ul>
@endif
</article>
</x-layout.user>
