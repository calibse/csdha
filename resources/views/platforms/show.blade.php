<x-layout.user editing route="platforms.index" class="platforms editing view">
    <h1>{{ $platform->name }}</h1>
    
    <p class="anchor-action-container"><a class="action" href="{{ route('platforms.edit', ['platform' => $platform->id], false) }}"><span class="icon"><x-icon.edit/></span> Edit this Platform</a></p>
    <p><span class="label">Description: </span>{{ $platform->description }}</p>
    <p><span class="label">Start date: </span>{{ $platform->start_date }}</p>
    <p><span class="label">End date: </span>{{ $platform->end_date }}</p>
    <p><span class="label">Progress: </span>{{ $platform->progress }}</p>
</x-layout.user>
