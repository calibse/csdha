@php
$spent = number_format(($fund->spent / $fund->collected) * 100, 2) . '%';
$remaining = number_format(($fund->remaining / $fund->collected) * 100, 2) . '%';
@endphp
<x-layout.user route="funds.index" class="funds">
    <h1 class="title">{{ $fund->event->title }}</h1>

    
    <p class="main-action">
        <a href="{{ route('funds.edit', ['fund' => $fund->id], false) }}"
        ><span class="icon"><x-icon.edit/></span> 
            Edit this Fund Allocation
        </a>
    </p>
    <dl class="values">
        <dt class="term">Collected</dt> 
        <dd class="value">{{ $fund->collected }}</dd>
        <dt class="term">Spent</dt> 
        <dd class="value">{{ $fund->spent }}</dd>
        <dt class="term">Remaining</dt> 
        <dd class="value">{{ $fund->remaining }}</dd>
    </dl>
    <div class="funds-meter">
        <div style="flex: 1 1 {{ $spent }}" class="meter-spent">{{ $spent }}</div>
        <div style="flex: 1 1 {{ $remaining }}" class="meter-remaining">{{ $remaining }}</div>
    </div>
    <aside class="legends">
        <p class="spent-legend">
            <span class="color-legend"></span>
            Spent
        </p>
        <p class="remaining-legend">
            <span class="color-legend"></span>
            Remaining
        </p>
    </aside>  
</x-layout.user>
