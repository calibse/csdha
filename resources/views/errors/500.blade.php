@php
$backRoute = auth()->check() ? '/home.html' : null;
@endphp
<x-layout.user content-view :$backRoute title="Application error">
<div class="article">
	<p>Something went wrong on our side. We’re working to fix it right away.</p>
</div>
</x-layout>
