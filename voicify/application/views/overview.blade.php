<ul class="discussions">
	
	@foreach($categories as $category)

		@include('category.partial.category')

		@foreach($category->discussions as $discussion)

			@include('category.partial.discussion')

		@endforeach

		<li class="more">
			<a href="{{ URL::to("category/{$category->slug}") }}" class="btn soft">See {{ $category->remaining }} more discussion{{ $category->remaining == 1 ? null : 's' }} <span>&rarr;</span></a>
		</li>

	@endforeach

</ul>