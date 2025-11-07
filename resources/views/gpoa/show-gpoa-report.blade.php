<x-layout.user :$backRoute title="GPOA Report" class="gpoa">
<div class="article">
@if (!$hasApproved)
	<p>There are no approved activities yet.</p>
@elseif (!$updated)
	<p>{{ $updateMessage }}</p>
@elseif ($fileRoute)
	<figure class="pdf-document">
		<div class="pdf-file">
			<object data="{{ $fileRoute }}" type="application/pdf">
			<p>
				Preview of this file is unsupported. You may download
				this file <a href="{{ $fileRoute }}">here</a>.
			</p>
			</object>
		</div>
		<figcaption class="caption">GPOA Report</figcaption>
	</figure>
@else
	<p>{{ $prepareMessage }}</p>
@endif
</div>
</x-layout.user>
