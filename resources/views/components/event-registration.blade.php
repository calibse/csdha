<x-layout.multi-step-form :$eventName :$formTitle>
@if ($step === 1)
    <main>
        <h2>Consent</h2>
        <p>Status</p>
        {{ $slot }}
    </main>
@else
    <section>
        <h2>Consent</h2>
        <p>Status</p>
    </section>
@endif
@if ($step === 2)
    <main>
        <h2>Identity</h2>
        <p>Status</p>
        {{ $slot }}
    </main>
@else
    <section>
        <h2>Identity</h2>
        <p>Status</p>
    </section>
@endif
@if ($step === 3)
    <main>
        <h2>Finish</h2>
        <p>Status</p>
        {{ $slot }}
    </main>
@else
    <section>
        <h2>Finish</h2>
        <p>Status</p>
    </section>
@endif

</x-layout>
