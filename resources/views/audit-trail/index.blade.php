<x-layout.user index title="Audit Trail" class="index audit">
@if ($audits->isNotEmpty())
    <article class="table-block">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Request ID</th>
                    <th>Action</th>
                    <th>Table</th>
                    <th>Columns</th>
                    <th>Primary Key</th>
                    <th>Request Time</th>
                    <th>IP Address</th>
                    <th>URL</th>
                    <th>HTTP Method</th>
                    <th>User ID</th>
                    <th>Session ID</th>
                    <th>User Agent</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($audits as $audit)
                <tr>
                    <td>{{ $audit->id }}</td>
                    <td>{{ $audit->request_id ?? '(Internal)' }}</td>
                    <td>{{ $audit->action }}</td>
                    <td>{{ $audit->table_name }}</td>
                    <td>{{ $audit->column_names }}</td>
                    <td>{{ $audit->primary_key }}</td>
                    <td>{{ $audit->request_time ?? '(Internal)' }}</td>
                    <td>{{ $audit->request_ip ?? '(Internal)' }}</td>
                    <td>{{ $audit->request_url ?? '(Internal)' }}</td>
                    <td>{{ $audit->request_method ?? '(Internal)' }}</td>
                    <td>{{ $audit->user_id ?? '(Internal)' }}</td>
                    <td>{{ $audit->session_id ?? '(Internal)' }}</td>
                    <td>{{ $audit->user_agent ?? '(Internal)' }}</td>
                    <td>{{ $audit->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@else
    <p>No audit records yet.</p>
@endif
</x-layout.user>
