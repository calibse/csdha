<x-layout.user content-view :$backRoute title="{{ $action }} GPOA Activity" class="gpoa form">
<div class="article">
	<x-alert error-bag="gpoa-activity_prepare" />
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
