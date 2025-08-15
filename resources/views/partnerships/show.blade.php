@php
$purpose_lines = explode("\n", $partnership->purpose);
$benefits_lines = explode("\n", $partnership->benefits);
$action_lines = explode("\n", $partnership->action);
$links_lines = explode("\n", $partnership->links);
$accomplished_by_lines = explode("\n", $partnership->accomplished_by);
@endphp
<x-layout.user editing route="partnerships.index" class="partnerships editing view">
    <h1>{{ $partnership->organization_name }}</h1>
    
    <p class="anchor-action-container"><a class="action" href="{{ route('partnerships.edit', ['partnership' => $partnership->id], false) }}"><span class="icon"><x-icon.edit/></span> Edit this Partnership</a></p>
    <p><span class="label">Purpose: </span></p>
    @foreach ($purpose_lines as $line)
	<p>{{ $line }}</p>
    @endforeach
    <p><span class="label">Benefits: </span></p>
    @foreach ($benefits_lines as $line)
	<p>{{ $line }}</p>
    @endforeach
    <p><span class="label">Action: </span></p>
    @foreach ($action_lines as $line)
	<p>{{ $line }}</p>
    @endforeach
    <p><span class="label">Links: </span></p>
    @foreach ($links_lines as $line)
	<p><a href="{{ $line }}">{{ $line }}</a></p>
    @endforeach
    <p><span class="label">Accomplished by: </span></p>
    @foreach ($accomplished_by_lines as $line)
	<p>{{ $line }}</p>
    @endforeach
    <p><span class="label">Officer: </span>{{ $partnership->officer }}</p>
</x-layout.user>
