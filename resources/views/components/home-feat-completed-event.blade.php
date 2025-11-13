<div style="background-color: #FFE898;" class="content-block">
	<div class="visual">
		<img src="{{ asset('icon/thiings-checkmark.png') }}">
	</div>
	<div class="info">
		<p class="subtitle">Recent Event</p>
		<p class="title">{{ $model->gpoaActivity->name }}</p>
	</div>
</div>
<x-home-feat-sib-links :$next :$prev/>
