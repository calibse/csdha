<x-layout.user index title="Meetings" class="meetings index">
	@can ('create', 'App\Models\Meeting')
	<p class="main-action"><a href="{{ route('meetings.create', [], false) }}"><span class="icon"><x-icon.add/></span> New meeting</a></p>
	@endcan
	@if ($meetings->isNotEmpty())
	<article class="list">
		@foreach ($meetings as $meeting)
		<section class="item">
			<h2 class="title"><a href="{{ route('meetings.show', ['meeting' => $meeting->id], false) }}">{{ $meeting->title }}</a></h2>
			<p class="desc">{{ substr($meeting->agenda, 0, 100) . (strlen($meeting->agenda) > 100 ? '...' : '') }}</p>
			<p class="date"><time>{{ date("F j, Y", strtotime($meeting->date)) }}</time></p>
		</section>
		@endforeach
	</article>
	{{ $meetings->links('paginator.simple') }}
	@else
	<p>No one has added anything yet</p>
	@endif
</x-layout.user>
