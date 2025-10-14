@if ($errors->any())
<aside {{ $attributes->merge(['class' => 'alert alert-error']) }}>
@if ($errors->count() > 1)
	<p>There are problems</p>
	<ul>
	@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
	@endforeach
	</ul>
@else
	<p>There is a problem</p>
	<p>{{ $errors->first() }}</p>
@endif
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
