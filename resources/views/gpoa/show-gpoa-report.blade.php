<x-layout.user :$backRoute title="GPOA Report" class="gpoa">
    <article class="article">
    @if ($fileRoute)
	@if (!$updated)
	<p>You’re viewing an outdated copy. The updated document is being processed.</p>
	@endif
        <figure class="pdf-document">
            <div class="pdf-file">
                <object data="{{ $fileRoute }}" type="application/pdf">
                    <p>
                        Preview of this file is unsupported. You may download
                        this file <a href="{{ $fileRoute }}">here</a>.
                    </p>
                </object>
            </div>
            <figcaption class="caption">GPOA Report</figcaption>
        </figure>
    @elseif ($empty)
        <p>There are no approved activities yet.</p>
    @else
        <p>The document is currently being generated. Please check back shortly.</p>
    @endif
    </article>
</x-layout.user>
