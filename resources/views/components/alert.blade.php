@php
$errorsToShow = $errorBag ? $errors->getBag($errorBag) : $errors;
@endphp
@if ($errorsToShow->any())
<aside id="{{ $window ? 'window-form-error' : 'form-error' }}" {{ $attributes->merge(['class' => 'alert alert-error']) }}>
	@if ($errorsToShow->count() > 1)
	<p>There are problems</p>
	<ul>
	@foreach ($errorsToShow->all() as $error)
		<li>{{ $error }}</li>
	@endforeach
	</ul>
	@else
	<p>There is a problem</p>
	<p>{{ $errorsToShow->first() }}</p>
	@endif
</aside>
@elseif (!$window && $slot->isNotEmpty())
<aside id="form-info" {{ $attributes->merge(['class' => 'alert alert-info']) }}>
	<p>{{ $slot }}</p>
</aside>
@elseif (!$window && session('status'))
<aside id="form-info" {{ $attributes->merge(['class' => 'alert alert-info']) }}>
	<p>{{ session('status') }}</p>
</aside>
@endif
