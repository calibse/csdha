<div style="background-color: #CBE2E8;" class="content-block">
	<div class="visual">
		<img src="{{ asset('icon/thiings-event.png') }}">
	</div>
	<div class="info">
		<p class="subtitle">Ongoing Event</p>
		<p class="title">
			<a href="{{ route('events.show', ['event' => $model->public_id]) }}">
				{{ $model->gpoaActivity->name }}
			</a>
		</p>
        @if ($model->dates()->exists())
		<p class="details">
			{{ $model->dates()->ongoingDates()->orderBy('date', 'desc')->orderBy('start_time')->first()->full_date }}
		</p>
	@endif
	</div>
	<x-home-feat-sib-links :$next :$prev />
</div>
