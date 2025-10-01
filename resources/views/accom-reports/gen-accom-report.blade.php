<x-layout.user :$backRoute title="Generate Accomplishment Report" class="accom-report generate">
    <article class="article">
        <div class="pdf-control">
            <x-alert/>
            <form>
                <div class="inline">
                    <p>
                        <label>Start date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}">
                    </p>
                    <p>
                        <label>End date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}">
                    </p>
                    <p>
                        <button>Generate</button>
                    </p>
                </div>
            </form>
        </div>
    @if ($fileRoute)
        <figure class="pdf-document">
            <div class="pdf-file">
                <object data="{{ $fileRoute }}" type="application/pdf">
                    <p>
                        Preview of this file is unsupported. You may download
                        this file <a href="{{ $fileRoute }}">here</a>.
                    </p>
                </object>
            </div>
            <figcaption class="caption">Accomplishment Report</figcaption>
        </figure>
    @elseif (!$start)
        <p>There are no approved accomplishment reports yet.</p>
    @elseif ($start && $empty)
        <p>No records available to generate.</p>
    @endif
    </article>
</x-layout.user>
