<x-layout.user index title="Funds" class="funds index">
	@can ('create', 'App\Models\Fund')
	<p class="main-action"><a href="{{ route('funds.create', [], false) }}"><span class="icon"><x-icon.add/></span> Add Fund Allocation</a></p>
	@endcan

	@if ($funds->isNotEmpty())
	<aside class="legends">
		<p class="spent-legend">
			<span class="color-legend"></span>
			Spent
		</p>
		<p class="remaining-legend">
			<span class="color-legend"></span>
			Remaining
		</p>
	</aside>		
	<article class="list">
		@foreach ($events as $event)
			@if ($event->fund)
				@php
				$spent = number_format(($event->fund->spent / $event->fund->collected) * 100, 2) . '%';
				$remaining = number_format(($event->fund->remaining / $event->fund->collected) * 100, 2) . '%';
				@endphp

		<section class="item">
			<h2 class="title"><a href="{{ route('funds.show', ['fund' => $event->fund->id], false) }}">{{ $event->title }}</a></h2>
		    <p><span class="label">Collected: </span>{{ $event->fund->collected }}</p>
		    <p><span class="label">Spent: </span>{{ $event->fund->spent }}</p>
		    <p><span class="label">Remaining: </span>{{ $event->fund->remaining }}</p>
			<div class="funds-meter">
				<div style="flex: 1 1 {{ $spent }}" class="meter-spent">{{ $spent }}</div>
				<div style="flex: 1 1 {{ $remaining }}" class="meter-remaining">{{ $remaining }}</div>
			</div>
		</section>
			@endif
		@endforeach
	</article>
	{{ $funds->links('paginator.simple') }}
	@else
	<p>No one has added anything yet</p>
	@endif
</x-layout.user>
