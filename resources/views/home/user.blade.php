<x-layout.user index id="home-content" class="home index" title="Home">
<div class="article">
@if ($gpoaActive)
	<div class="infos" id="home-content_infos">
	@include('home.infos')
	</div>
	<p id="home-content_no-gpoa" style="display: none">There is no active GPOA right now.</p>
@else
	<div class="infos" id="home-content_infos" style="display: none">
	@include('home.infos')
	</div>
	<p id="home-content_no-gpoa">There is no active GPOA right now.</p>
@endif
</div>
</x-layout.user>
