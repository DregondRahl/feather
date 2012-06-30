<?php namespace Service;

use URI;
use View;
use Repository\Category;

class Menu {

	public static function make($selected = null)
	{
		return new static($selected);
	}

	protected $results;

	protected $archived = false;

	protected $selected;

	public function __construct($selected)
	{
		$this->results = Category::order_by('position', 'asc');

		if(is_null($selected) and URI::is('categories/*'))
		{
			$this->selected = URI::segment(2);
		}
	}

	public function archived()
	{
		$this->archived = true;

		return $this;
	}

	protected function get()
	{
		$this->results = $this->results->get();

		return $this;
	}

	protected function generate($categories, $parent = 0, $depth = 0)
	{
		$return = array();

		foreach($categories as $id => $category)
		{
			$category = (object) array_merge($category->attributes, array(
				'children'		 => array(),
				'parent'		 => false,
				'child'			 => ($category->parent_id > 0),
				'selected'		 => ($category->uri == $this->selected),
				'depth'			 => $depth
			));

			if($category->parent_id === $parent)
			{
				unset($categories[$id]);

				$category->children = $this->generate($categories, $category->id, $depth + 1);

				$category->parent = !empty($category->children);

				$return[$category->id] = $category;
			}
		}

		return $return;
	}

	public function html($view = 'partial.categories.category', $results = null)
	{
		if(is_null($results))
		{
			$this->get();

			$results = $this->generate($this->results);
		}

		$html = '';

		foreach($results as $category)
		{
			$html .= View::make($view, compact('category'))->render();

			if($category->parent)
			{
				$html .= $this->html($view, $category->children);
			}
		}

		return $html;
	}

	public function __call($method, $parameters)
	{
		if(in_array($method, array('html', 'array')))
		{
			$this->get();

			$categories = $this->generate($this->results);

			if($method == 'array')
			{
				return $categories;
			}

			
		}
	}

	/**
	 * Get an array of categories with parents containing their children. The menu can be generated
	 * for specific groupings. By default, all categories are fetched for the menu.
	 * 
	 * 		<code>
	 * 			// Generate a menu array for all categories.
	 * 			$menu = Repository\Category::menu();
	 * 
	 * 			// Generate a menu array for all child categories.
	 * 			$menu = Repository\Category::menu('children');
	 * 
	 * 			// Generate a menu array for the current selected item.
	 * 			$menu = Repository\Category::menu('selected');
	 * 		</code>
	 * 
	 * @param  string  $group
	 * @param  bool    $archived
	 * @return array
	 */

}