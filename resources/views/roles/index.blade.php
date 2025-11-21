<x-layout.user content-view index title="Roles" class="events index form">
<article class="article">
	<x-alert/>
	<form method="post" action={{ $formAction }} >
	@method('PUT')
	@csrf
		<p>
			<label>{{ ucwords($role->name) }}</label>
			<select multiple size="5" name="{{ strtolower($role->name) }}[]">
			@if ($authUserHasRole)
				<option disabled value="">
					{{ auth()->user()->fullName }} (selected)
				</option>
			@endif
			@foreach ($selectedUsers as $user)
				<option value="{{ $user->public_id }}" selected>
					{{ $user->fullName }}
				</option>
			@endforeach
			@foreach ($users as $user)
				<option value="{{ $user->public_id }}">
					{{ $user->fullName }}
				</option>
			@endforeach
			</select>
		</p>
		<p class="form-submit">
			<button>Update</button>
		</p>
	</form>
</article>
</x-layout.user>
