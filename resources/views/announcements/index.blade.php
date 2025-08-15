<x-layout.user index title="Announcements" class="index announcements">

    @if ($announcements->isNotEmpty())
    <article class="list">
        @foreach ($announcements as $announcement)
        <article class="item">
            <h2>{{ $announcement->title }}</h2>
        </article>
        @endforeach
    </article>
    @else
    <p>No one has added anything yet</p>
    @endif

    {{ $announcements->links('paginator.simple') }}
</x-layout.user>