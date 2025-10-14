<x-layout.user :$backRoute class="settings" title="Edit Student Courses">
	<x-slot:toolbar>
		<a href="{{ $createRoute }}">
			<img class="icon" src="{{ asset('icon/light/plus-circle-duotone.png') }}">
			<span class="text">Add student course</span>
		</a>
	</x-slot:toolbar>
	<div class="article">
		<x-alert/>
		<ul class="item-list">
		@foreach ($courses as $course)
			<li class="item">
				<span class="content">{{ "{$course->name} ({$course->acronym})"  }}</span>
				<span class="context-menu">
					<form method="get" action="{{ route('settings.students.courses.confirm-destroy', ['course' => $course->id]) }}"> 
						<button>Delete</button>
					</form>
				</span>
			</li>
		@endforeach
		</ul>
	</div>
</x-layout.user>
