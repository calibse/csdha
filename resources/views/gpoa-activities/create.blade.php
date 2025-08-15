<x-layout.user :$backRoute title="{{ $activity ? 'Update' : 'Add' }} GPOA Activity" class="gpoa form">
    <article class="article">
        <x-alert type="error"/>
        <form method="post" action="{{ $formAction }}">
            @if ($activity)
                @method('PUT')
            @endif
            @csrf
            <p>
                <label>Name of Activity</label>
                <input name="name" value="{{ old('name') ?? $activity?->name }}">
            </p>
            <p>
                <label>Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date') ?? $activity?->start_date }}">
            </p>
            <p>
                <label>End Date (optional)</label>
                <input type="date" name="end_date" value="{{ old('end_date') ?? $activity?->end_date }}">
            </p>
            <p>
                <label>Objectives</label>
                <textarea name="objectives">{{ old('objectives') ?? $activity?->objectives }}</textarea>
            </p>
            <p>
                <label>Participants Description</label>
                <input name="participants_description" value="{{ old('participants_description') ?? $activity?->participants }}">
            </p>
            <p>
                <label>Participants Year Levels</label>
                <select multiple size="6" name="participant_year_levels[]">
                    <option value="0" {{ (in_array('0', (old('participant_year_levels') ?? [])) || ($activity && $allAreParticipants)) ? 'selected' : null }}>
                        All CS Members
                    </option>
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
                </select>
            </p>
            <p>
                <label>Type of Activity</label>
                <input name="type_of_activity" list="activity_types" autocomplete="off" value="{{ old('type_of_activity') ?? $activity?->type?->name }}">
                <datalist id="activity_types">
                @foreach ($activityTypes as $type)
                    <option value="{{ $type->name }}">
                @endforeach
                </datalist>
            </p>
            <p>
                <label>Mode</label>
                <input name="mode" list="modes" autocomplete="off" value="{{ old('mode') ?? $activity?->mode?->name }}">
                <datalist id="modes">
                @foreach ($modes as $mode)
                    <option value="{{ $mode->name }}">
                @endforeach
                </datalist>
            </p>
            <p>
                <label>Partnership (optional)</label>
                <input name="partnership" list="partnership_types" autocomplete="off" value="{{ old('partnership') ?? $activity?->partnershipType?->name }}">
                <datalist id="partnership_types">
                @foreach ($partnershipTypes as $type)
                    <option value="{{ $type->name }}">
                @endforeach
                </datalist>
            </p>
            <p>
                <label>Proposed Budget (optional)</label>
                <input type="number" name="proposed_budget" value="{{ old('proposed_budget') ?? $activity?->proposed_budget }}">
            </p>
            <p>
                <label>Source of Fund (optional)</label>
                <input name="fund_source" list="fund_sources" autocomplete="off" value="{{ old('fund_source') ?? $activity?->fundSource?->name }}">
                <datalist id="fund_sources">
                @foreach ($fundSources as $source)
                    <option value="{{ $source->name }}">
                @endforeach
                </datalist>
            </p>
            <p>
                <label>Event Head</label>
                <select multiple name="event_heads[]">
                    @if (!$activity || ($activity && $authUserIsEventHead))
                    <option disabled>{{ auth()->user()->full_name }} (Added)</option>
                    @endif
                    <option value="0" {{ (in_array('0', (old('event_heads') ?? [])) || ($activity && $allAreEventHeads)) ? 'selected' : null }}>
                        All CSCB Officers
                    </option>
                    @foreach ($selectedEventHeads as $selectedEventHead)
                    <option value="{{ $selectedEventHead->public_id }}" selected>{{ $selectedEventHead->full_name }}</option>
                    @endforeach
                    @foreach ($eventHeads as $eventHead)
                    <option value="{{ $eventHead->public_id }}">{{ $eventHead->full_name }}</option>
                    @endforeach
                </select>
            </p>
            <p>
                <label>Co-head (optional)</label>
                <select multiple name="coheads[]"> 
                    @foreach ($selectedCoheads as $selectedCohead)
                    <option value="{{ $selectedCohead->public_id }}" selected>{{ $selectedCohead->full_name }}</option>
                    @endforeach
                    @foreach ($coheads as $cohead)
                    <option value="{{ $cohead->public_id }}">{{ $cohead->full_name }}</option>
                    @endforeach
                </select>
            </p>
            <p class="form-submit">
                <button>{{ $activity ? 'Update' : 'Add' }} Activity</button>
            </p>
        </form>
    </article>
</x-layout.user>
