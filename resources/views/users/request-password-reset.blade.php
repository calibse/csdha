<x-layout.user form class="password-reset form" :$backRoute title="Reset Password">
<div class="article">
	<x-alert/>
	<form method="post" action="{{ $formAction }}">
	@csrf
		<p>
			<label for="email">Email</label>
			<input type="email" name="email" id="email">
		</p>
		<p class="form-submit">
			<button>Send Link</button>
		<p>
	</form>
</div>
</x-layout>
