@use ('App\Services\Format')
<x-layout.user index title="Audit Trail" class="index audit">
    <article class="article">
@if ($audits->isNotEmpty())
        <table class="table-4">
		<colgroup>
			<col span="4" style="width: 25%">
		</colgroup>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                    <th>Table</th>
                    <th>Columns</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($audits as $audit)
                <tr>
                    <td><a href="{{ route('audit.show', ['audit' => $audit->id]) }}">{{ Format::date($audit->created_at) }}</a></td>
                    <td>{{ $audit->action }}</td>
                    <td>{{ $audit->table_name }}</td>
                    <td>{{ $audit->column_names }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
	{{ $audits->links('paginator.simple') }}
	@else
	    <p>No audit records yet.</p>
	@endif
    </article>
</x-layout.user>
