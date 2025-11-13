<div style="background-color: #FCCCDA;" class="content-block">
	<div class="visual">
		<img src="{{ asset('icon/thiings-document.png') }}">
	</div>
	<div class="info">
		<p class="subtitle">New Activity Plan</p>
		<p class="title">{{ $model->name }}</p>
	</div>
</div>
<x-home-feat-sib-links :$next :$prev/>
