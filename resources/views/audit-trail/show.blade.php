<x-layout.user :$backRoute class="audit" title="Audit Trail">
<div class="article">
	<table class="main-table table-2">
		<colgroup>
			<col style="width: 10rem">
		</colgroup>
		<tbody>
			<tr>
				<th>Date</th>
				<td>{{ $createdAt }}</td>
			</tr>
			<tr>
				<th>Action</th>
				<td>{{ $action }}</td>
			</tr>
			<tr>
				<th>Table name</th>
				<td>{{ $tableName }}</td>
			</tr>
			<tr>
				<th>Column names</th>
				<td>{{ $columnNames }}</td>
			</tr>
			<tr>
				<th>Primary Key</th>
				<td>{{ $primaryKey }}</td>
			</tr>
			<tr>
				<th>Request ID</th>
				<td>{{ $requestId }}</td>
			</tr>
			<tr>
				<th>Request URL</th>
				<td>{{ $requestUrl }}</td>
			</tr>
			<tr>
				<th>Request method</th>
				<td>{{ $requestMethod }}</td>
			</tr>
			<tr>
				<th>Request time</th>
				<td>{{ $requestTime }}</td>
			</tr>
			<tr>
				<th>User ID</th>
				<td>{{ $userId }}</td>
			</tr>
			<tr>
				<th>User agent</th>
				<td>{{ $userAgent }}</td>
			</tr>
			<tr>
				<th>Session ID</th>
				<td>{{ $sessionId }}</td>
			</tr>
			<tr>
				<th>Date record updated</th>
				<td>{{ $updatedAt }}</td>
			</tr>
		</tbody>
	</table>
</div>
</x-layout>
