<li class="category {{ $category->parent ? 'parent' : null }} {{ $category->selected ? 'selected' : null }}">
	
	{{ HTML::link('category/' . $category->uri, $category->name, array('class' => 'tooltip-ui-categories', 'title' => $category->description)) }}

</li>