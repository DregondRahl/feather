<li class="discussion group">
	<div class="category">
		{{ HTML::link("category/{$discussion->category->slug}", $discussion->category->name, array('class' => 'btn soft')) }}
	</div>

	<div class="meta">
		{{ HTML::link("discussion/{$discussion->id}/{$discussion->slug}", $discussion->title, array('class' => 'title')) }}

		<p>
			Started by {{ HTML::link("profile/{$discussion->author->slug}", $discussion->author->username) }}, {{ Date::meta($discussion->created_at) }}
		</p>

		@if($discussion->recent)
		<p>
			Last reply by <a href="">Dayle</a>, on <span title="24th June, 2012 at 6:45am">23th June</span>
		</p>
		@endif
	</div>

	<div class="stats">
		<div class="views">
			<span class="title">Views</span>
			<span class="number">{{ $discussion->views }}</span>
		</div>
		<div class="replies">
			<span class="title">Replies</span>
			<span class="number">{{ $discussion->replies }}</span>
		</div>
	</div>
</li>