<x-layout.user class="events" :$backRoute title="Edit Evaluation Comments">
<div class="article">
	<x-alert/>
	<nav>
		<h2 class="title">Comment Types</h2>
		<ul class="content-list">
		@foreach ($typeRoutes as $typeName => $typeRoute)
			<li>
				<a href="{{ $typeRoute }}">
					{{ $typeName }}
				</a>
			</li>
		@endforeach
		</ul>
	</nav>
@if (!$commentType)
	<p>No comment type selected.</p>
@elseif ($selectedComments->isNotEmpty() || $unselectedComments->isNotEmpty())
	<form method="post" action="{{ $formAction }}">
	@csrf
	@method('PUT')
		<fieldset>
			<legend>{{ $commentType }}</legend>
		@foreach ($selectedComments as $comment)
			<p class="checkbox">
				<input id="comment[{{ $comment->id }}]" name="comments[{{ $comment->id }}]" type="checkbox" value="1" checked>
				<label for="comment[{{ $comment->id }}]">{{ $comment->comment }}</label>
			</p>
		@endforeach
		@foreach ($unselectedComments as $comment)
			<p class="checkbox">
				<input id="comment[{{ $comment->id }}]" name="comments[{{ $comment->id }}]" type="checkbox" value="1">
				<label for="comment[{{ $comment->id }}]">{{ $comment->comment }}</label>
			</p>
		@endforeach
		</fieldset>
		<p class="form-submit">
			<button
			@cannot ('update', $event)
				disabled
			@endcannot
			>Update</button>
		</p>
	</form>
@else
	<p>Nothing here yet.</p>
@endif
</div>
</x-layout>
