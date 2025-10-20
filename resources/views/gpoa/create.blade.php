<x-layout.user form route="gpoa.index" title="{{ $update ? 'Edit' : 'Create' }} GPOA" class="gpoa form">
    <article class="article">
        <x-alert type="error"/>
        <form method="post" action="{{ $update ? route('gpoa.update') : route('gpoa.store') }}">
            @if ($update)
                @method('PUT')
            @endif
            @csrf
            <fieldset>
                <legend>Academic Term</legend>
            @foreach ($terms as $term)
                <p class="checkbox">
                    <input id="academic-term-{{ $term->id }}" type="radio" name="academic_term" value="{{ $term->id }}" {{ (old('academic_term') ?? (string)$gpoa?->academicPeriod->term->id) === (string)$term->id ? 'checked' : null }}>
                    <label for="academic-term-{{ $term->id }}">{{ $term->label }}</label>
                </p>
            @endforeach
            </fieldset>
            {{--
            <p>
                <label>Academic term</label>
                <select name="academic_term">
                    <option value="">-- Select --</option>
                @if ($errors->any())
                    @foreach ($terms as $term)
                    <option value="{{ $term->id }}" {{ old('academic_term') === (string)$term->id ? 'selected' : null }}>{{ $term->label }}</option>
                    @endforeach
                @else
                    @foreach ($terms as $term)
                    <option value="{{ $term->id }}" {{ $gpoa?->academicPeriod->term()->is($term) ? 'selected' : null }}>{{ $term->label }}</option>
                    @endforeach
                @endif
                </select>
            </p>
            --}}
            <p>
                <label>Academic start date</label>
                <input type="date" name="start_date" value="{{ old('start_date') ?? $gpoa?->academicPeriod->start_date }}">
            </p>
            <p>
                <label>End date</label>
                <input type="date" name="end_date" value="{{ old('end_date') ?? $gpoa?->academicPeriod->end_date }}">
            </p>
            <p>
                <label>Head of Student Services</label>
                <input name="head_of_student_services" value="{{ old('head_of_student_services') ?? $gpoa?->academicPeriod->head_of_student_services }}">
            </p>
            <p>
                <label>Branch Director</label>
                <input name="branch_director" value="{{ old('branch_director') ?? $gpoa?->academicPeriod->branch_director }}">
            </p>
            <p class="form-submit">
                <button>{{ $update ? 'Update' : 'Create' }}</button>
            </p>
        </form>
    </article>
</x-layout.user>
