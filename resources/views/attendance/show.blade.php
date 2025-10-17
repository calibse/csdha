<x-layout.user title="Attendance" route='user.home' class="attendance form">
    <article class="article">
        <noscript>
		<p>
			This page requires JavaScript, which your browser doesn't support.
		</p>
        </noscript>
    @if ($dates->isNotEmpty())
        <template id="scanner-feature">
            <form class="event-selector">
            @if ($dates->count() === 1)
                <p>{{ $dates[0]->event->gpoaActivity->name }}</p>
                <input hidden id="event" value="{{ $dates[0]->public_id }}">
            @else
                <p>
                    <select id="event">
                        <option value="">-- Select Event --</option>
                    @foreach ($dates as $date)
                        <option value="{{ $date->public_id }}">{{ $date->event->gpoaActivity->name }}</option>
                    @endforeach
                    </select>
                </p>
            @endif
            </form>
            <div id="id-scanner">
                <script type="application/json" class="status-values">
{
    "success": {
        "class": "success",
        "text": "ID Checked In"
    },
    "failure": {
        "class": "failure",
        "text": "ID Not Found"
    },
    "idle": {
        "class": "idle",
        "text": "Ready to Scan"
    },
    "processing": {
        "class": "processing",
        "text": "Checking your ID. Please wait..."
    }
}
                </script>
                <p class="indicator">
                    <span class="timeout"></span>
                    <span class="signal"></span>
                    <span class="status">
                        <span class="text"></span>
                    </span>
                    {{--
                    <template class="statuses">
                        <span class="success status">
                            <span class="icon"><x-phosphor-check-circle/></span>
                            <span class="text">
                                ID Checked In
                            </span>
                        </span>
                        <span class="failure status">
                            <span class="icon"><x-phosphor-x-circle/></span>
                            <span class="text">
                                ID Not Found
                            </span>
                        </span>
                        <span class="idle status">
                            <span class="icon"><x-phosphor-eye/></span>
                            <span class="text">
                                Ready to Scan
                            </span>
                        </span>
                        <span class="processing status">
                            <span class="icon"><x-phosphor-eye/></span>
                            <span class="text">
                                Checking your ID. Please wait...
                            </span>
                        </span>
                    </template>
                    --}}
                </p>
                <video class="video"></video>
            </div>
        </template>
    @else
        <template id="scanner-feature">
            <p>There are no ongoing events today.</p>
        </template>
    @endif
    </article>
</x-layout.user>
