<x-layout.user has-toolbar index title="Central Body" class="index positions">
	<x-slot:toolbar>
	@can ('create', 'App\Models\Position')
	    <a href="{{ route('positions.create') }}">
			<img class="icon" src="{{ asset('icon/light/plus-circle.png') }}">

	    	<span class="text">Add new officer position</span>
    	</a>
	@endcan
	</x-slot:toolbar>
    <div class="article">
    @if ($positions->isNotEmpty())
	    <table class="table-2">
            <colgroup>
                <col style="width: 30%">
                <col style="width: 70%">
            </colgroup>
			<thead>
			    <tr>
					<th>Position</th>
					<th>Officer Name</th>
			    </tr>
			</thead>
			<tbody>
		    @foreach ($positions as $position)
				<tr>
				    <td class="position-name">
					@can ('create', 'App\Models\Position')
					    <a href="{{ route('positions.show', ['position' => $position->id]) }}">{{ $position->name }}</a>
					@else
					    {{ $position->name }}
					@endcan
				    </td>
				    <td>{{ $position->user->full_name ?? '' }}</td>
				</tr>
		    @endforeach
			</tbody>
	    </table>
    @else
        <p>No one has added anything yet</p>
    @endif
   	</div>
</x-layout.user>
