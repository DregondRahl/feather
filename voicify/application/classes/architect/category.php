<?php namespace Architect;

use URI;
use Repository\Category as Category_Repository;

class Category extends Architect {

	/**
	 * The URI of the selected category.
	 * 
	 * @var string
	 */
	public $selected;

	/**
	 * Create a new Category Architect instance.
	 * 
	 * @param  string  $selected
	 * @return object
	 */
	public static function make($selected = null)
	{
		$results = Category_Repository::order_by('position', 'asc');

		if(is_null($selected) and URI::is('categories/*'))
		{
			$selected = URI::segment(2);
		}

		$categories = new static($results);

		$categories->selected = $selected;

		return $categories;
	}

	/**
	 * Categories must either be archived or not.
	 * 
	 * @param  bool  $switch
	 * @return object
	 */
	public function archived($switch = true)
	{
		$this->results->where_archived((int) $switch);

		return $this;
	}

	/**
	 * Generates an array of categories.
	 * 
	 * @param  array  $categories
	 * @param  int    $parent
	 * @param  int    $depth
	 * @return array
	 */
	protected function generate($categories, $parent = 0, $depth = 0)
	{
		$return = array();

		foreach($categories as $id => $category)
		{
			$category->attributes = array_merge($category->attributes, array(
				'children' => array(),
				'parent'   => false,
				'child'	   => ($category->parent_id > 0),
				'selected' => ($category->uri == $this->selected),
				'depth'	   => $depth
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

}