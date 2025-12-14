<div id="{{ $id }}" {{ $attributes->merge(['class' => 'window']) }} 
{{--
style="display: none;"
--}}
>
	<div id="{{ $id . '_title-bar' }}" class="window-title">
		<span id="{{ $id . '_title-text' }}" class="text">{{ $title }}</span>
	</div>
	<div class="window-content">
		<x-alert window :error-bag="$id" />
		{{ $slot }}
	</div>
</div>
