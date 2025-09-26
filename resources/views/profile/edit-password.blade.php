<x-layout.user class="profile form" :$backRoute title="Change Password">
    <article class="article">
        <x-alert/>
        <form method="post" action="{{ $formAction }}">
            @csrf
            @method('PUT')
			<p>
				<label for="old_password">Old Password</label>
				<input type="password" id="old_password" name="old_password">
			</p>
			<p>
				<label for="password">New Password</label>
				<input type="password" id="password" name="password">
			</p>
			<p>
				<label for="password_confirmation">Confirm password</label>
				<input type="password" id="password_confirmation" name="password_confirmation">
			</p>
            <p class="form-submit">
                <button>Update</button>
            </p>
        </form>
    </article>
</x-layout>
