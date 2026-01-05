@use ('App\Services\Format')
<x-layout.user form :$backRoute title="Closed GPOAs" class="gpoa form">
<div class="article">
@if ($gpoas->isNotEmpty())
	<table class="articles main-table table-2">
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
			<tr class="{{ $loop->last ? 'last-row' : null }}">
				<td><a href="{{ route('gpoas.show', ['gpoa' => $gpoa->public_id]) }}">{{ $gpoa->full_academic_period }}</td>
				<td class="last-row-cell">{{ Format::date($gpoa->closed_at) }}</td>
			</tr>
		@endforeach
		</tbody>	
	</table>
	{{ $gpoas->links('paginator.simple') }}
@else
	<p>Nothing here yet.</p>
@endif
</div>
</x-layout>
