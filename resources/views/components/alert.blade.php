@if ($errors->any())
<aside {{ $attributes->merge(['class' => 'alert alert-error']) }}>
    <p>We couldn’t submit the form due to these issues:</p>
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
    </ul>
</aside>
@elseif ($slot->isNotEmpty())
<aside {{ $attributes->merge(['class' => 'alert alert-info']) }}>
    <p>{{ $slot }}</p>
</aside>
@elseif (session('status'))
<aside {{ $attributes->merge(['class' => 'alert alert-info']) }}>
    <p>{{ session('status') }}</p>
</aside>
{{--
@else
<aside {{ $attributes->merge(['class' => 'alert alert-info']) }}>
    <p>Test alert</p>
</aside>
--}}
@endif
