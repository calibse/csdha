<x-layout.user :$backRoute title="Accomplishment Report" class="event">
	<x-slot:toolbar>
		<a
		@can ('update', $event)
			href="{{ $editRoute }}"
		@endcan
		>
			<span class="icon"><x-phosphor-pencil-simple/></span>
			<span class="text">Edit</span>
		</a>
		<a
		@can ('viewAnyAccomReport', 'App\Models\Event')
			href="{{ $changeBgRoute }}"
		@endcan
		>
			<span class="icon"><x-phosphor-pencil-simple/></span>
			<span class="text">Change Background</span>
		</a>
	@if ($actions['submit'])
		<a
		@can('submitAccomReport', $event)
			href="{{ $submitRoute }}"
		@endcan
		>
			<span class="icon"><x-phosphor-arrow-fat-line-right/></span>
			<span class="text">Submit</span>
		</a>
	@endif
	@if ($actions['return'])
		<a
		@can('returnAccomReport', $event)
			href="{{ $returnRoute }}"
		@endcan
		>
			<span class="icon"><x-phosphor-arrow-fat-line-left/></span>
			<span class="text">Return</span>
		</a>
	@endif
	@if ($actions['approve'])
		<a
		@can('approveAccomReport', $event)
			href="{{ $approveRoute }}"
		@endcan
		>
			<span class="icon"><x-phosphor-check-circle/></span>
			<span class="text">Approve</span>
		</a>
	@endif
	</x-slot:toolbar>
	<article class="article">
		<x-alert/>
	@if ($accomReport)
		<p>Status: {{ $accomReport->full_status }}</p>
	@endif
	@if ($accomReport?->comments)
		<pre>"{{ $accomReport?->comments }}"</pre>
	@endif
	@if ($date)
		<p>{{ $date }}</p>
	@endif
		<figure class="pdf-document">
			<div class="pdf-file">
				<object data="{{ $fileRoute }}" type="application/pdf">
				<p>
					Preview of this file is unsupported. You may download
					this file <a href="{{ $fileRoute }}">here</a>.
				</p>
				</object>
			</div>
			<figcaption class="caption">Accomplishment Report</figcaption>
		</figure>
	</article>
</x-layout.user>
