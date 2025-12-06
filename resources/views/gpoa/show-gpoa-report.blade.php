<x-layout.user form :$backRoute title="GPOA Report" class="gpoa form">
<div class="article">
@if (!$hasApproved)
	<p>There are no approved activities yet.</p>
@elseif (!$updated)
	<p class="has-icon">
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium.png') }}">
                <span class="text">
			{{ $updateMessage }}
		</span>
	</p>
@elseif ($fileRoute)
	<div class="pdf-document">
		<figure>
			<div class="pdf-file">
				<object data="{{ rtrim(url('/'), '/') . $fileRoute}}">
					<iframe src="/viewerjs/#..{{ $fileRoute}}">
						<p>
							Preview of this file is unsupported. You may download this 
							file <a href="{{ rtrim(url('/'), '/') . $fileRoute }}">here</a>.
						</p>
					</iframe>
				</object>
			</div>
			<figcaption>
				<div class="caption">GPOA Report</div>
			</figcaption>
		</figure>
	</div>
@else
	<p class="has-icon">
		<img class="icon" src="{{ asset('icon/small/light/hourglass-medium.png') }}">
                <span class="text">
			{{ $prepareMessage }}
		</span>
	</p>
@endif
</div>
</x-layout.user>
