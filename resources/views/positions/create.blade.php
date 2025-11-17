<x-layout.user form class="positions form" title="Create position" route="positions.index">
	<article class="article">
		<x-alert/>
		<form action="{{ route('positions.store') }}" method="POST">
			@csrf
			<p>
				<label>Position name</label>
				<input name="position_name" value="{{ old('position_name') }}">
			</p>
			<p>
				<label>Position order</label>
				<input type="number" name="position_order" value="{{ old('position_order') }}">
			</p>
			<p>
				<label>Officer</label>
				<select name="officer">
					<option value="">-- Select --</option>
					<option value="0" {{ old('officer') === "0" ? 'selected' : null }}>None</option>
				@foreach ($users as $user)
					<option value="{{ $user->public_id }}" {{ old('officer') === (string) $user->public_id ? 'selected' : null }}>{{ $user->fullName }}</option>
				@endforeach
				</select>
			</p>
			<p><label>Permissions</label></p>
		@foreach ($resources as $resource)
			<fieldset>
				<legend>
					{{ ucwords(str_replace('-', ' ', $resource->name)) }}
				</legend>
			@foreach($resource->actions as $action)
				<p class="checkbox-field">
					<input name="permissions[]" type="checkbox" value="{{ $action->permission->id }}" {{ in_array($action->permission->id, (old('permissions') ?? [])) ? 'checked' : null }}
						@cannot('addPerm', $action->permission)
						disabled
						@endcannot
					>
					<label>{{ ucwords(str_replace('-', ' ', $action->name)) }}</label>
				</p>
			@endforeach
			</fieldset>
		@endforeach
			<p class="form-submit">
				<button>Save</button>
			</p>
		</form>
	</article>
</x-layout>
