<x-layout.multi-step-form :$eventName :$formTitle :$submitRoute>
    <p>{{ $event->regisForm?->introduction }}</p>
    <h2>Data Privacy Act of 2012</h2>
    <x-alert/>
    <form id="current-form" method="post" action="{{ $submitRoute }}">
        @csrf
        <p>
            This data privacy notice complies with RA 10173, the Data Privacy 
            Act of 2012. It acknowledges that the information shared by 
            respondents is confidential and meant solely for the organization 
            named. All personal data is legally protected against unauthorized 
            use or disclosure. Consequently, any actions such as printing, 
            copying, sharing, or forwarding this information are strictly 
            forbidden.
        </p>
        <p>
            <input type="checkbox" name="consent" id="consent-1" value="1" {{ ($inputs['consent'] ?? null) === '1' ? 'checked' : null }}>
            <label for="consent-1">I agree</label>
        </p>
        {{-- 
        <p class="radio-field">
            <input type="radio" name="consent" id="consent-0" value="0" {{ ($inputs['consent'] ?? null) === '0' ? 'checked' : null }}>
            <label for="consent-0">I disagree</label>
        </p>
        --}}
    </form>
</x-layout.multi-step-form>
