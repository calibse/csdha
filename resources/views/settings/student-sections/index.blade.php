<x-layout.user :$backRoute class="settings" title="Edit Student Sections">
	<x-slot:toolbar>
		<a href="{{ $createSectionRoute }}">
			<img class="icon" src="{{ asset('icon/light/plus-circle-duotone.png') }}">

			<span class="text">Add student section</span>
		</a>
	</x-slot:toolbar>
	<div class="article">
		<x-alert/>
		<ul class="item-list">
		@foreach ($sections as $section)
			<li class="item">
				<span class="content">{{ $section->section }}</span>
				<span class="context-menu">
					<form method="get" action="{{ route('settings.students.sections.confirm-destroy', ['section' => $section->id]) }}"> 
						<button>Delete</button>
					</form>
				</span>
			</li>
		@endforeach
		</ul>
	</div>
</x-layout.user>
