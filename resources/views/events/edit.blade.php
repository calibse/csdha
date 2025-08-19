<x-layout.user class="events form" :$backRoute title="Edit event">
	<article class="article">
		<x-alert/>
	    <form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
			@method("PUT")
			@csrf
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
            <p>
            	<input value="1" type="checkbox" id="auto-attendance" name="automatic_attendance" {{ $event->automatic_attendance ? 'checked' : null }}>
            	<label for="auto-attendance">Automatic Attendance <small>(for students only)</small></label>
            </p>
            <p>
            	<input value="1" type="checkbox" id="accept-eval" name="accept_evaluation" {{ $event->accept_evaluation ? 'checked' : null }}>
            	<label for="accept-eval">Accept Evaluation <small>(for students only)</small></label>
            </p>
			<p>
				<label>Tag <small>(for QR code label)</small></label>
				<input name="tag" value="{{ $event->tag }}">
			</p>
			<p>
				<label>Venue</label>
				<input name="venue" value="{{ $event->venue }}">
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
				<label>Registration form</label>
				<a href="{{ $regisRoute }}">Edit here</a>
			</p>
			<p>
				<label>Evaluation form</label>
				<a href="{{ $evalRoute }}">Edit here</a>
			</p>
			<p class="form-submit">
			    <button type="submit">Update</button>
			</p>
	    </form>
	</article>
</x-layout>
