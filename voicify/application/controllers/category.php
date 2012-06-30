<?php

class Category_Controller extends Base_Controller {

	public function get_index()
	{
		
	}

	public function get_category($uri)
	{
		if($category = Repository\Category::show($uri))
		{
			$this->layout->nest('content', 'category.index', compact('category'));
		}
		else
		{

		}
	}

}