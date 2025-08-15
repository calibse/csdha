@php
function dha_date($date)
{
  return date("jS \of F Y h:i A", strtotime($date));
}
$agenda_lines = explode("\n", $meeting->agenda);
@endphp

<x-layout.user editing route="meetings.index" class="meetings editing view">
    <h1>{{ $meeting->title }}</h1>
    
    @if ($meeting->user_id === auth()->user()->id)
	<p class="anchor-action-container"><a class="action" href="{{ route('meetings.edit', ['meeting' => $meeting->id], false) }}"><span class="icon"><x-icon.edit/></span> Edit this meeting</a></p>
    @endif
    
    <p><span class="label">Posted by</span> {{ $meeting->user->fullName }}</p>
    <p><span class="label">Date:</span> <time>{{ $meeting->date }}</time></p>
    <p><span class="label">Venue:</span> {{ $meeting->venue }}</p>
    <p><span class="label">Participants:</span> {{ $meeting->participants }}</p>
    @if ($meeting->minutes_file)
	<p><a href="{{ route('meetings.showMinutes', ['meeting' => $meeting->id], false) }}">Minutes File</a></p>
    @endif
    <p><span class="label">Agenda:</span></p>
    @foreach ($agenda_lines as $line)
	<p>{{ $line }}</p>
    @endforeach
</x-layout.user>
