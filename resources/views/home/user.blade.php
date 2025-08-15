<x-layout.user index class="home index" title="Home">
	{{--<article class="account">
		<figure class="avatar">
			@if (auth()->user()->avatar_filepath)
			<img src="{{ route('profile.showAvatar', [], false) }}">
			@else
			<x-icon.avatar/>
			@endif
		</figure>
		<article class="info">
			<p class="name"><span class="label">Name: </span>{{ auth()->user()->full_name }}</p>
			<p class="role"><span class="label" >Position: </span>{{ auth()->user()->position->name ?? '' }}</p>
			<p class="main-action"><a 
				href="{{ route('profile.edit', [], false) }}"
				><span class="icon">
					<x-icon.edit/>
				</span>
				Edit profile
			</a></p>
		</article>
	</article>--}}

	{{--<article class="announcements list">
		<h2 class="title">Announcements</h2>
		<p class="main-action">
			<button popovertarget="create-announcement">
				<span class="icon">
					<x-icon.add/>
				</span>
				New
			</button>
		</p>
		<article class="item">
			<h3 class="title"><a href="#">Some announcement</a></h3>
			<p>This happened..</p>
			<time datetime="2001-01-01">January 1, 2000</time>
		</article>
		<article class="item">
			<h3 class="title"><a href="#">Some announcement</a></h3>
			<p>This happened..</p>
			<time datetime="2001-01-01">January 2, 2000</time>
		</article>
		<p>
			<a href="{{ route('announcements.index', [], false) }}">
				Show more announcements
			</a>
		</p>
	</article>
	<dialog popover id="create-announcement">
		<h3 class="title">New Announcement</h3>
		<form method="POST"
			action="{{ route('announcements.store', [], false) }}"
		>
			<p>
				<label>Title</label>
				<input type="text"
					name="title"
					maxlength="255" 
					required
				>
			</p>
			<p>
				<label>Introduction</label>
				<textarea 
					name="introduction"
					maxlength="255"
					required
				></textarea>
			</p>
			<p>
				<label>Message</label>
				<textarea 
					id="message"
					name="message"
					maxlength="65535"
					required
				></textarea>
			</p>
			<p>
				<button 
					popovertarget="create-announcement" 
					type="button"
				>
					Cancel
				</button>
				<button type="submit">Post</button>
			</p>
		</form>
	</dialog>--}}
</x-layout.user>
