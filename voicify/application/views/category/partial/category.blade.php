<li class="category">
	<h2>{{ HTML::link("category/{$category->slug}", $category->name) }}</a></h2>

	@if($category->parent)
		<ul class="children">

		@foreach($category->children as $child)
			<li><a href="">{{ $child->name }}</a></li>
		@endforeach

	</ul>
	@endif
</li>