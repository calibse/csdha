<x-layout.user :$backRoute title="Accomplishment Report" class="gpoa">
<div class="article">
@if ($fileRoute)
	<div class="pdf-document">
		<figure class="pdf-file">
			<iframe src="/viewerjs/#..{{ $fileRoute}}">
				<p>
					Preview of this file is unsupported. You may download this 
					file <a href="{{ rtrim(url('/'), '/') . $fileRoute }}">here</a>.
				</p>
			</iframe>
			<figcaption>
				<div class="caption">Accomplishment Report</div>
			</figcaption>
		</figure>
	</div>
@else
	<p>{{ $prepareMessage }}</p>
@endif
</div>
</x-layout.user>
