<x-layout.user index title="Platforms" class="index platforms">
	@can ('create', 'App\Models\Platform')
	<p class="main-action"><a href="{{ route('platforms.create', [], false) }}"><span class="icon"><x-icon.add/></span> Add Platform</a></p>
    @endcan

    @if ($platforms->isNotEmpty())
    <article class="list">
		@foreach ($platforms as $platform)
	    <section class="item">
		<h2 class="title"><a href="{{ route('platforms.show', ['platform' => $platform->id], false) }}">{{ $platform->name }}</a></h2>
		<p class="desc">{{ substr($platform->description, 0, 100) . (strlen($platform->description) > 100 ? '...' : '') }}</p>
		<p>Progress: {{ $platform->progress }}</p>
	    </section>
		@endforeach
	</article>
	{{ $platforms->links('paginator.simple') }}
    @else
    <p>No one has added anything yet</p>
    @endif
</x-layout.user>
