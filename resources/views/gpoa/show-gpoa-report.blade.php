<x-layout.user form :$backRoute title="GPOA Report" class="gpoa form">
<div class="article">
@if (!$hasApproved)
	<p>There are no approved activities yet.</p>
@elseif (!$updated)
	<p class="has-icon">
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium-fill.png') }}">
                <span class="text">
			{{ $updateMessage }}
		</span>
	</p>
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
	<p class="has-icon">
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium-fill.png') }}">
                <span class="text">
			{{ $prepareMessage }}
		</span>
	</p>
@endif
</div>
</x-layout.user>
