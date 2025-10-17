<x-layout.user class="events form" :$backRoute title="Edit event">
<div class="article">
	<x-alert/>
	<form method="post" action="{{ $formAction }}" enctype="multipart/form-data">
	@method("PUT")
	@csrf
		<fieldset>
			<legend>Record Attendance</legend>
			<p class="checkbox">
				<input id="record-attendance-0" name="record_attendance[]" type="checkbox" value="0" 
				@if ($errors->any())
					{{ in_array('0', old('record_attendance') ?? []) ? 'checked' : null }}
				@else
					{{ $event->participant_type ? null : 'checked' }}
				@endif
				>
				<label for="record-attendance-0">Not Applicable</label>
			</p>
			<p class="checkbox">
				<input id="record-attendance--1" name="record_attendance[]" type="checkbox" value="-1" 
				@if ($errors->any())
					{{ in_array('0', old('record_attendance') ?? []) ? 'checked' : null }}
				@else
					{{ $officersOnly ? 'checked' : null }}
				@endif
				>
				<label for="record-attendance--1">Officers only</label>
			</p>
		@foreach ($participants as $participant)
			<p>
				<input id="record-attendance-{{ $participant->id }}" type="checkbox" name="record_attendance[]" value="{{ $participant->id }}" 
				@if ($errors->any())
					{{ in_array((string)$participant->id, old('record_attendance') ?? []) ? 'checked' : null }}
				@else
					{{ $selectedParticipants->contains($participant) ? 'checked' : null }}
				@endif
				>
				<label for="record-attendance-{{ $participant->id }}">{{ $participant->label }}</label>
			</p>
		@endforeach
		</fieldset>
		<p>
			<label>Student Courses</label>
			<select multiple size="5" name="student_courses[]">
			@foreach ($selectedCourses as $course)
				<option selected value="{{ $course->id }}">{{ $course->name }}</option>
			@endforeach
			@foreach ($courses as $course)
				<option value="{{ $course->id }}">{{ $course->name }}</option>
			@endforeach
			</select>
		</p>
		<p class="checkbox">
			<input value="1" type="checkbox" id="auto-attendance" name="automatic_attendance" 
			@if ($errors->any())
				{{ old('automatic_attendance') === '1' ? 'checked' : null }}
			@else
				{{ $event->automatic_attendance ? 'checked' : null }}
			@endif
			>
			<label for="auto-attendance">Automatic Attendance <small>(for students only)</small></label>
		</p>
		<p class="checkbox">
			<input value="1" type="checkbox" id="accept-eval" name="accept_evaluation" 
			@if ($errors->any())
				{{ old('accept_evaluation') === '1' ? 'checked' : null }}
			@else
				{{ $event->accept_evaluation ? 'checked' : null }}
			@endif
			>
			<label for="accept-eval">Accept Evaluation <small>(for students only)</small></label>
		</p>
		<p>
			<label>Tag <small>(for QR code label)</small></label>
			<input name="tag" value="{{ $errors->any() ? old('tag') : $event->tag }}">
		</p>
		<p>
			<label>Venue</label>
			<input name="venue" value="{{ $errors->any() ? old('venue') : $event->venue }}">
		</p>
		<p>
			<label>Time Zone</label>
			<select name="timezone">
				<option value="" {{ !old('timezone') ? 'selected' : null }}>-- Select --</option>
			@foreach ($timezones as $timezone)
				<option
				@if ($errors->any())
					{{ strtolower(old('timezone')) === strtolower($timezone) ? 'selected' : null }}
				@else
					{{ strtolower($event->timezone) === strtolower($timezone) ? 'selected' : null }}
				@endif
				>{{ $timezone }}</option>
			@endforeach
			</select>
		</p>
		<p>
			<label>Evaluation Delay in Hours</label>
			<input type="number" name="evaluation_delay_hours" value="{{ $errors->any() ? old('evaluation_delay_hours') : $event->evaluation_delay_hours }}">
		</p>
		<p>
			<label>Description</label>
			<textarea name="description">{{ $errors->any() ? old('description') : $event->description }}</textarea>
		</p>
		<p>
			<label>Narrative</label>
			<textarea name="narrative">{{ $errors->any() ? old('narrative') : $event->narrative }}</textarea>
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
</div>
</x-layout>
