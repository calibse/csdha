@if ($previousStepRoute)
    <p class="submit-button">
        <button form="previous-form">Back</button>
        <button form="current-form">{{ $lastStep ? 'Submit' : 'Next' }}</button>
    </p>
@elseif (!$end)
    <p class="submit-button">
        <button form="current-form">Next</button>
    </p>
@endif
@if ($previousStepRoute)
    <form hidden id="previous-form" method="get" action="{{ $previousStepRoute }}">
    @if (isset($prevInput) && $prevInput->hasActualContent())
        {{ $prevInput }}
    @endif
    </form>
@endif
