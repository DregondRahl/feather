<ul class="categories group">

	<li class="all">
		{{ HTML::link('category/all', 'All Categories') }}
	</li>

	{{ Service\Menu::make()->html() }}

</ul>