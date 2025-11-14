<div style="background-color: #D0EB6E;" class="content-block">
	<div class="visual">
		<img src="{{ asset('icon/thiings-calendar.png') }}">
	</div>
	<div class="info">
		<p class="subtitle">Upcoming Event</p>
		<p class="title">
			<a href="{{ route('events.show', ['event' => $model->public_id]) }}">
				{{ $model->gpoaActivity->name }}
			</a>
		</p>
	@if ($model->dates()->exists())
                <p class="details">
                        {{ $model->dates()->orderBy('date', 'desc')->orderBy('start_time')->first()->full_date }}
                </p>
	@endif
	</div>
</div>
<x-home-feat-sib-links :$next :$prev/>
