<x-layout.user :$backRoute title="Closed GPOAs" class="gpoa">
	<div class="article">
		<table class="table-2">
			<colgroup>
				<col style="width: 30%">
				<col style="width: 70%">
			</colgroup>
			<thead>
				<tr>
					<th>Academic Period</th>
					<th>Date closed</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($gpoas as $gpoa)
				<tr>
					<td><a href="{{ route('gpoas.show', ['gpoa' => $gpoa->public_id]) }}">{{ $gpoa->full_academic_period }}</td>
					<td>{{ $gpoa->closed_at->format(config('app.date_format')) }}</td>
				</tr>
			@endforeach
		 	</tbody>	
		</table>
		{{ $gpoas->links('paginator.simple') }}
	</div>
</x-layout>
