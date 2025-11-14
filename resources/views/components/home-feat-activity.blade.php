<div style="background-color: #FCCCDA;" class="content-block">
	<div class="visual">
		<img src="{{ asset('icon/thiings-document.png') }}">
	</div>
	<div class="info">
		<p class="subtitle">New Activity Plan</p>
		<p class="title">
			<a href="{{ route('gpoa.activities.show', ['activity' => $model->public_id]) }}">
				{{ $model->name }}
			</a>
		</p>
		<p class="details">
			Added by {{ $model->eventHeads()->first()->full_name }}
		</p>
	</div>
</div>
<x-home-feat-sib-links :$next :$prev/>
