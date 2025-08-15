<x-layout.user route="gpoa.index" title="GPOA Report" class="gpoa">
    <article class="article">
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
    </article>
</x-layout.user>
