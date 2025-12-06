@php
$errorsToShow = $errorBag ? $errors->getBag($errorBag) : $errors;
@endphp
@if ($errorsToShow->any())
<div id="{{ $window ? 'window-form-error' : 'form-error' }}" {{ $attributes->merge(['class' => 'alert alert-error']) }}>
	<aside>
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
</div>
@elseif (!$window && $slot->isNotEmpty())
<div id="form-info" {{ $attributes->merge(['class' => 'alert alert-info']) }}>
	<aside>
		<p>{{ $slot }}</p>
	</aside>
</div>
@elseif (!$window && session('status'))
<div id="form-info" {{ $attributes->merge(['class' => 'alert alert-info']) }}>
	<aside>
		<p>{{ session('status') }}</p>
	</aside>
</div>
@endif
