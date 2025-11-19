@php
$backRoute = auth()->check() ? '/home.html' : null;
@endphp
<x-layout.user content-view :$backRoute title="Access denied">
<div class="article">
	<p>Sorry, you’re not allowed to perform this action.</p>
</div>
</x-layout>
