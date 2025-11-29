<x-layout.user content-view class="events form" :$backRoute title="Edit Registration Form">
    <article class="article">
        <form method="post" action="{{ $formAction }}">
            @method('PUT')
            @csrf
            <p>
                <label>Introduction</label>
                <textarea name="introduction">{{ old('introduction') ?? $regisForm?->introduction }}</textarea>
            </p>
            <p class="form-submit">
                <button
		@cannot('update', $event)
			disabled
		@endcannot
		>Update</button>
            </p>
        </form>
    </article>
</x-layout.user>
