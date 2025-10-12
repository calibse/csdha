<x-layout.user $backRoute title="Old GPOAs" class="gpoa">
	<div class="article">
		<table class="table-2">
			<colgroup>
				<col style="width: 30%">
				<col style="width: 70%">
			</colgroup>
			<thead>
				<tr>
					<th>Academic Period</th>
					<th>Number of activities</th>
					<th>Date closed</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				</tr>
		 	</tbody>	
		</table>
		{{ $gpoa->links('paginator.simple') }}
	</div>
</x-layout>
