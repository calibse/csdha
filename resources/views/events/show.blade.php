<x-layout.user :$backRoute class="events event" title="Event">
    <x-slot:toolbar>
        <a
            @can ('update', $event)
            href="{{ $editRoute }}"
            @endcan
        >
            <img class="icon" src="{{ asset('icon/light/wrench-duotone.png') }}">

            <span class="text">Settings</span>
        </a>
        <a
            @can ('viewAccomReport', $event)
            href="{{ $genArRoute }}"
            @endcan
        >
            <img class="icon" src="{{ asset('icon/light/file-plus-duotone.png') }}">
            <span class="text">{{ $event->accomReport?->approved_at ? 'View' : 'Submit' }} AR</span>
        </a>
    </x-slot:toolbar>
    <div class="article document">
        <x-alert/>

        <h2>{{ $activity->name }}</h2>
        <table class="table-2">
            <colgroup>
                <col style="width: 12em;">
            </colgroup>
            <tr>
                <th>Date <span class="edit-link">[<a href="{{ $dateRoute }}">Edit</a>]</span></th>
                <td>
                    <ul>
                    @foreach ($event->compactDates() as $date)
                        <li>{{ $date }}</li>
                    @endforeach
                    </ul>
                </td>
            </tr>
            @can ('register', $event)
            <tr>
                <th>Registration Form <span class="edit-link">[<a>Edit</a>]</span></th>
                <td>
                    <a href="{{ $regisRoute }}">
                        {{ $regisRoute }}
                    </a>
                </td>
            </tr>
            @endcan
            <tr>
                <th>Description <span class="edit-link">[<a id="event-description_edit-button" href="{{ $descriptionRoute }}">Edit</a>]</span></th>
                <td id="event-description"><pre>{{ $event->description }}</pre></td>
            </tr>
            <tr>
                <th>Narrative <span class="edit-link">[<a id="event-narrative_edit-button" href="{{ $narrativeRoute }}">Edit</a>]</span></th>
                <td id="event-narrative"><pre>{{ $event->narrative }}</pre></td>
            </tr>
            <tr>
                <th>Venue <span class="edit-link">[<a id="event-venue_edit-button" href="{{ $venueRoute }}">Edit</a>]</span></th>
                <td id="event-venue">{{ $event->venue }}</td>
            </tr>
		@can ('evaluate', $event)
            <tr>
                <th>Evaluation Form <span class="edit-link">[<a>Edit</a>]</span></th>
                <td><a>Show preview</a></td>
            </tr>
            <tr>
                <th>Evaluation Result <span class="edit-link">[<a>Edit</a>]</span></th>
                <td>0 comments selected</td>
            </tr>
		@endcan
            <tr>
                <th>Attachments</th>
                <td><a href="{{ $attachmentRoute }}">Show</a></td>
            </tr>
            @can ('recordAttendance', $event)
            <tr>
                <th>Attendance</th>
                <td><a href="{{ $attendanceRoute }}">Show</a></td>
            </tr>
            @endcan
            <tr>
                <th>Participants / Beneficiaries</th>
                <td>{{ $activity->participants }}</td>
            </tr>
            <tr>
                <th>Type of Activity</th>
                <td>{{ $activity->type }}</td>
            </tr>
            <tr>
                <th>Objectives</th>
                <td><pre>{{ $activity->objectives }}</pre></td>
            </tr>
            <tr>
                <th>Event Head</th>
                <td>
                    <ul>
                        @foreach ($eventHeads as $eventHead)
                        <li>{{ $eventHead->full_name }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @if ($coheads->isNotEmpty())
            <tr>
                <th>Co-head</th>
                <td>
                    <ul>
                        @foreach ($coheads as $cohead)
                        <li>{{ $cohead->full_name }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endif
        </table>


        {{--
        <h3>Date</h3>
        @foreach ($event->compactDates() as $date)
        <p>{{ $date }}</p>
        @endforeach

        <h3>Venue</h3>
        <p>{{ $event->venue }}</p>

        <h3>Participants/Beneficiaries</h3>
        <p>{{ $activity->participants }}</p>

        <h3>Type of Activity</h3>
        <p>{{ $activity->type }}</p>

        <h3>Objectives</h3>
        <pre>{{ $activity->objectives }}</pre>

        <h3>Description</h3>
        <pre>{{ $event->description }}</pre>

        <h3>Narrative</h3>
        <pre>{{ $event->narrative }}</pre>

        <h3>Event Head</h3>
        <p>Event Head</p>
        <ul>
            @foreach ($eventHeads as $eventHead)
            <li>{{ $eventHead->full_name }}</li>
            @endforeach
        </ul>
        @if ($coheads->isNotEmpty())
        <p>Co-head</p>
        <ul>
                @foreach ($coheads as $cohead)
            <li>{{ $cohead->full_name }}</li>
                @endforeach
        </ul>
        @endif

        @can ('register', $event)
        <h3>Registration Form</h3>
        <p>
            <a href="{{ $regisRoute }}">
                {{ $regisRoute }}
            </a>
        </p>
        @endcan

        @can ('recordAttendance', $event)
        <h3>Attendance</h3>
        <p><a href="{{ $attendanceRoute }}">Attendance</a></p>
        @endcan

        @can ('evaluate', $event)
        <h3>Evaluation Form</h3>
        <p>
            <a href="{{ $evalRoute }}">
                {{ $evalRoute }}
            </a>
        </p>
        @endcan
        --}}
    </div>
<x-window class="form" id="event-description_edit" title="Edit event description">
        <form method="post" action="{{ $descriptionFormAction }}">
        @csrf
        @method('PUT')
                <p>
                        <label for="event-description_field">Description</label>
                        <textarea id="event-description_field" name="description">{{ old('description') }}</textarea>
                </p>
                <p class="form-submit">
                        <button type="button" id="event-description_edit_close">Cancel</button>
                        <button>Update</button>
                </p>
        </form>
</x-window>
<x-window class="form" id="event-narrative_edit" title="Edit event narrative">
        <form method="post" action="{{ $narrativeFormAction }}">
        @csrf
        @method('PUT')
                <p>
                        <label for="event-narrative_field">Narrative</label>
                        <textarea id="event-narrative_field" name="narrative">{{ old('narrative') }}</textarea> </p>
                <p class="form-submit">
                        <button type="button" id="event-narrative_edit_close">Cancel</button>
                        <button>Update</button>
                </p>
        </form>
</x-window>
<x-window class="form" id="event-venue_edit" title="Edit event venue">
        <form method="post" action="{{ $venueFormAction }}">
        @csrf
        @method('PUT')
                <p>
                        <label for="event-venue_field">Venue</label>
                        <input id="event-venue_field" name="venue" value="{{ old('venue') }}">
                </p>
                <p class="form-submit">
                        <button type="button" id="event-venue_edit_close">Cancel</button>
                        <button>Update</button>
                </p>
        </form>
</x-window>
</x-layout.user>
