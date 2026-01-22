<x-layout.user content-view index class="home index" title="Home">
<div class="article">
	<dl>
		<dt>Overall disk usage</dt>
		<dd>{{ $diskUsed }} / {{ $diskTotal }}</dd>
		<dt>Database disk usage</dt>
		<dd>{{ $dbUsage }}</dd>
		<dt>Total size of files</dt>
		<dd>{{ $fileStorageUsage }}</dd>
		<dt>Memory limit per request</dt>
		<dd>{{ $memoryLimit }}</dd>
		<dt>Upload size limit per file</dt>
		<dd>{{ $uploadSizeLimit }}</dd>
	</dl>
</div>
</x-layout.user>
