@php
$routeParams = ['gspoa' => $gspoa->id]
@endphp
<x-layout.user form title="Edit GSPOA" class="gspoas form" route="gspoas.show" :$routeParams>
    <article class="article">
        <form method="POST" action="{{ route('gspoas.update', ['gspoa' => $gspoa->id]) }}">
            @method('PUT')
            @csrf
            <p>
                <label>Program Title</label>
                <input name="program_title" required value="{{ $gspoa->program_title }}">
            </p>
            <p>
                <label>Executive Summary</label>
                <textarea name="executive_summary" required>{{ $gspoa->executive_summary }}</textarea>
            </p>
            <p>
                <label>Objectives</label>
                <textarea name="objectives" required>{{ $gspoa->objectives }}</textarea>
            </p>
            <p>
                <label>Marketing and Promotion</label>
                <textarea name="promotion" required>{{ $gspoa->promotion }}</textarea>
            </p>
            <p>
                <label>Logistics</label>
                <textarea name="logistics" required>{{ $gspoa->logistics }}</textarea>
            </p>
            <p>
                <label>Financial Plan</label>
                <textarea name="financial_plan" required>{{ $gspoa->financial_plan }}</textarea>
            </p>
            <p>
                <label>Safety and Security</label>
                <textarea name="safety" required>{{ $gspoa->safety }}</textarea>
            </p>
            <p class="form-submit">
                <button>Update</button>
            </p>
        </form>
    </article>
</x-layout.user>
