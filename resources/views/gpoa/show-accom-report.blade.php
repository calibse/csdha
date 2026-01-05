<x-layout.user style="accom-report.scss" :$backRoute title="Accomplishment Report" class="gpoa print">
<x-slot:toolbar>
	<button id="print-button">
		<img class="icon" src="{{ asset('icon/light/printer.svg') }}">
		<span class="text">Print</span>
	</button>
</x-slot>
<div class="article">
	<x-accom-report :$events :$editors :$approved :$president />
{{--
@if ($fileRoute)
	<div class="pdf-document">
		<figure>
			<div class="pdf-file">
				<object data="{{ rtrim(url('/'), '/') . $fileRoute }}">
					<iframe src="/viewerjs/#..{{ $fileRoute}}">
						<p>
							Preview of this file is unsupported. You may download this 
							file <a href="{{ rtrim(url('/'), '/') . $fileRoute }}">here</a>.
						</p>
					</iframe>
				</object>
			</div>
			<figcaption>
				<div class="caption">Accomplishment Report</div>
			</figcaption>
		</figure>
	</div>
@else
	<p>{{ $prepareMessage }}</p>
@endif
--}}
</div>
</x-layout.user>
