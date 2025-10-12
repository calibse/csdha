<x-layout.user :$backRoute class="settings" title="Edit Student Sections">
	<x-slot:toolbar>
		<a href="{{ $createSectionRoute }}">
			<span class="icon"><x-phosphor-plus-circle/></span>
			<span class="text">Add student section</span>
		</a>
	</x-slot:toolbar>
	<div class="article">
		<x-alert/>
		<ul class="item-list">
			<li class="item">
				<span class="content">{{ $itemname }}</span>
				<span class="context-menu">
					<form method="post" action="{{ $deleteRoute }}"> 
					@csrf
					@method('DELETE')
						<button form="delete-item">Delete</button>
					</form>
				</span>
			</li>
		</ul>
	</div>
</x-layout.user>
