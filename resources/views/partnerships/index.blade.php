<x-layout.user index title="Partnerships" class="index partnerships view">
	@can ('create', 'App\Models\Partnership')
	<p class="main-action"><a href="{{ route('partnerships.create', [], false) }}"><span class="icon"><x-icon.add/></span> Add Partnership</a></p>
    @endcan

    @if ($partnerships->isNotEmpty())
    <article class="list">
		@foreach ($partnerships as $partnership)
	    <section class="item">
		<h2 class="title"><a href="{{ route('partnerships.show', ['partnership' => $partnership->id], false) }}">{{ $partnership->organization_name }}</a></h2>
		<p>{{ $partnership->purpose }}</p>
	    </section>
		@endforeach
	</article>
	{{ $partnerships->links('paginator.simple') }}
    @else
	<p>No one has added anything yet</p>
    @endif
</x-layout.user>
