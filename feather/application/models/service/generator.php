<?php namespace Service;

use URI;
use View;
use Repository\Category;

class Generator {

	

	/**
	 * Array of results.
	 * 
	 * @var array
	 */
	protected $results;

	protected $archived = false;

	public $selected;

	public function __construct($results)
	{
		$this->results = $results;
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