<x-layout.user class="events form" :$backRoute title="Edit event">
	<article class="article">
		<x-alert/>
	    <form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
			@method("PUT")
			@csrf
            <fieldset>
                <legend>Record Attendance</legend>
                <p class="checkbox">
                    <input id="record-attendance-0" name="record_attendance[]" type="checkbox" value="0" {{ ($event->participant_type || in_array('0', old('record_attendance') ?? [])) ? null : 'checked' }}>
                    <label for="record-attendance-0">Not Applicable</label>
                </p>
                <p class="checkbox">
                    <input id="record-attendance--1" name="record_attendance[]" type="checkbox" value="-1" {{ (in_array('0', (old('participant_year_levels') ?? [])) || ($officersOnly)) ? 'checked' : null }}>
                    <label for="record-attendance--1">Officers only</label>
                </p>
            @foreach($participants as $participant)
                <p>
                    <input id="record-attendance-{{ $participant->id }}" type="checkbox" name="record_attendance[]" value="{{ $participant->id }}" {{ (in_array((string)$participant->id, (old('participant_year_levels') ?? [])) || $selectedParticipants->contains($participant)) ? 'checked' : null }}>
                    <label for="record-attendance-{{ $participant->id }}">{{ $participant->label }}</label>
                </p>
            @endforeach
            </fieldset>
            {{--
            <p>
                <label>Record Attendance</label>
                <select multiple size="8" name="record_attendance[]">
	                <option value="0" {{ ($event->participant_type || in_array('0', old('record_attendance') ?? [])) ? null : 'selected' }}>
	                	Not Applicable
	                </option>
	                <option value="-1" {{ (in_array('0', (old('participant_year_levels') ?? [])) || ($officersOnly)) ? 'selected' : null }}>
	                	Officers only
	                </option>
                	<optgroup label="-- Students only --">
	                    @foreach($selectedParticipants as $selectedParticipant)
	                    <option value="{{ $selectedParticipant->id }}" selected>
	                        {{ $selectedParticipant->label }}
		                    </option>
	                    @endforeach
	                    @foreach($participants as $participant)
	                    <option value="{{ $participant->id }}">
	                        {{ $participant->label }}
	                    </option>
	                    @endforeach
	                </optgroup>
                </select>
            </p>
            --}}
            <p class="checkbox">
            	<input value="1" type="checkbox" id="auto-attendance" name="automatic_attendance" {{ $errors->any() ? (old('automatic_attendance') === '1' ? 'checked' : null) : ($event->automatic_attendance ? 'checked' : null) }}>
            	<label for="auto-attendance">Automatic Attendance <small>(for students only)</small></label>
            </p>
            <p class="checkbox">
                <input value="1" type="checkbox" id="accept-eval" name="accept_evaluation" {{ $errors->any() ? (old('accept_evaluation') === '1' ? 'checked' : null) : ($event->accept_evaluation ? 'checked' : null) }}>
            	<label for="accept-eval">Accept Evaluation <small>(for students only)</small></label>
            </p>
			<p>
				<label>Tag <small>(for QR code label)</small></label>
				<input name="tag" value="{{ old('tag') ?? $event->tag }}">
			</p>
			<p>
				<label>Venue</label>
				<input name="venue" value="{{ old('venue') ?? $event->venue }}">
			</p>
			<p>
				<label>Time Zone</label>
				<select name="timezone">
                    <option value="">-- Select --</option>
                @foreach ($timezones as $timezone)
                    <option value="{{ $timezone }}" {{ strtolower(old('timezone') ?? $event->timezone) === strtolower($timezone) ? 'selected' : null }}>{{ $timezone }}</option>
                @endforeach
                </select>
			</p>
			<p>
				<label>Evaluation Delay in Hours</label>
				<input type="number" name="evaluation_delay_hours" value="{{ old('evaluation_delay_hours') ?? $event->evaluation_delay_hours }}">
			</p>
			<p>
				<label>Description</label>
				<textarea name="description">{{ $event->description }}</textarea>
			</p>
			<p>
				<label>Narrative</label>
				<textarea name="narrative">{{ $event->narrative }}</textarea>
			</p>
			<p>
				<label>Dates</label>
				<a href="{{ $dateRoute }}">Edit here</a>
			</p>
			<p>
				<label>Attachments</label>
				<a href="{{ $attachmentRoute }}">Edit here</a>
			</p>
			<p>
				<label>Registration form</label>
				<a href="{{ $regisRoute }}">Edit here</a>
			</p>
			<p>
				<label>Evaluation form</label>
				<a href="{{ $evalRoute }}">Edit here</a>
			</p>
			<p>
				<label>Attendees Evaluation</label>
				<a href="{{ $commentsRoute }}">Edit here</a>
			</p>
			<p class="form-submit">
			    <button type="submit">Update</button>
			</p>
	    </form>
	</article>
</x-layout>
