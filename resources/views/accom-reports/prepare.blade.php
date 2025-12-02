<x-layout.user content-view :$backRoute title="{{ $action }} Accom. Report" class="gpoa form">
<div class="article">
	<x-alert error-bag="accom-report_prepare" />
	<form method="post" action="{{ $formAction }}">
	@method('PUT')
	@csrf
		<p>
			<label>Comments</label>
			<textarea name="comments"></textarea>
		</p>
		<p class="form-submit">
			<button>{{ $action }}</button>
		</p>
	</form>
</div>    
</x-layout.user>
