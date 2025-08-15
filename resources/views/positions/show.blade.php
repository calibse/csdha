@use('App\Models\Permission')
<x-layout.user class="positions form" title="Edit position" route="positions.index">
	<article class="article">
		<x-alert/>
		<form action="{{ route('positions.update', ['position' => $position->id ], false) }}" method="POST">
			@method('PUT')
			@csrf
			<p>
				<label>Position name</label>
				<input name="position_name" type="text" value="{{ old('position_name') ?? $position->name }}"
				@cannot('rename', $position)
					readonly
				@endcannot
				>
			</p>
			<p>
				<label>Officer</label>
				<select name="officer">
					<option value="">Select Member</option>
				@can('removeOfficer', $position)
					<option value="0">None</option>
				@endcan
				@if ($officer)				
					<option value="{{ $officer->public_id }}" selected>
						{{ $officer->fullName }}
					</option>
				@endif
				@foreach ($users as $user)
					<option value="{{ $user->public_id }}">{{ $user->fullName }}</option>
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
					<input id="perm-{{ $action->permission->id }}" name="permissions[]" type="checkbox" value="{{ $action->permission->id }}"
						{{ $position->permissions()->whereKey($action->permission->id)->exists() ? 'checked' : '' }}
						@cannot ('changePerm', [$position, $action->permission])
						disabled
						@endcannot
					>
					<label for="perm-{{ $action->permission->id }}">
						{{ ucwords(str_replace('-', ' ', $action->name)) }}
					</label>
				</p>
				@endforeach
			</fieldset>
		@endforeach
			<p class="form-submit">
				<button>Update</button>
			</p>
		</form>
		<form action="{{ route('positions.confirmDestroy', ['position' => $position->id]) }}">
			<p class="form-submit">
				<button 
				@cannot ('delete', $position)
					disabled
				@endcannot
				>Delete position</button>
			</p>
		</form>
	</article>
	{{--
	<dialog id="confirm-delete" popover>
		<form method="POST" action="{{ route('positions.destroy', ['id' => $position->id], false) }}">
			@method('DELETE')
			@csrf
			<p>Are you sure you want to delete the <strong>{{ $position->name }}</strong> position?</p>
			<p class="form-submit">
				<button popovertarget="confirm-delete" type="button">
					Cancel
				</button>
				<button type="submit">
					Delete
				</button>
			</div>
		</form>
	</dialog>
	--}}
</x-layout>
