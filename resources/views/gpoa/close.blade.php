<x-layout.user content-view :$backRoute class="gpoa form" title="Close GPOA">
<div class="article">
	<p>
		Are you sure you want to close this GPOA for {{ $gpoa->academicPeriod->term->label }} A.Y. {{ $gpoa->academicPeriod->year_label }}?
	</p> 
	<div class="submit-buttons">
		<form action="{{ $backRoute }}">
			<button>Cancel</button>
		</form>
		<form method="post" action="{{ $closeRoute }}">
		@csrf
		@method('PUT')
			<button>Close GPOA</button>
		</form>
	</div>
</div>
</x-layout.user>
