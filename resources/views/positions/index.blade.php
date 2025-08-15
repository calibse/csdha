<x-layout.user index title="Central Body" class="index positions">
	<x-slot:toolbar>
	@can ('create', 'App\Models\Position')
	    <a href="{{ route('positions.create') }}">
	    	<span class="icon"><x-phosphor-plus-circle/></span>
	    	<span class="text">Add new officer position</span>
    	</a>
	@endcan
	</x-slot:toolbar>

@if ($positions->isNotEmpty())
    <article class="table-block">
	    <table>
			<thead>
			    <tr>
					<th>Officer Name</th>
					<th>Position</th>
			    </tr>
			</thead>
			<tbody>
		    @foreach ($positions as $position)
				<tr>
				    <td>{{ $position->user->full_name ?? '' }}</td>
				    <td>
					@can ('create', 'App\Models\Position')
					    <a href="{{ route('positions.show', ['position' => $position->id]) }}">{{ $position->name }}</a>
					@else 
					    {{ $position->name }}
					@endcan
				    </td>
				</tr>
		    @endforeach
			</tbody>
	    </table>
   	</article>
@else
    <p>No one has added anything yet</p>
@endif
</x-layout.user>
