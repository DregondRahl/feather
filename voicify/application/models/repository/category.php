<?php namespace Repository;

use DB;
use Str;
use URI;
use Nestify;
use Architect;
use Config as C;

class Category extends Nestify {

	public static $table = 'category';
	public static $timestamps = false;

	/**
	 * Return a category based on its slug with all discussions for that category.
	 * 
	 * @param  string  $slug
	 * @return object
	 */
	public static function show($slug)
	{
		if($slug == 'all')
		{
			return (object) array(
				'name'		  => 'All Categories',
				'description' => 'Some awesome description.'
			);
		}
		else
		{
			return static::where_slug($slug)->first();
		}
	}

	/**
	 * Returns an overview used for the index page by default. The overview consists
	 * of all the categories and their immediate children plus a user defined amount
	 * of discussions taken from any category within the parent.
	 * 
	 * @return object
	 */
	public static function overview()
	{
		// Get an array of categories and their children from the Architect.
		$categories = Architect\Category::make()->array();

		// We'll now generate a flattened array of all the categories with the key being the ID of
		// that category so we can still use that in the query for getting all the discussions.
		$flattened = array();

		foreach($categories as $category)
		{
			$category->remaining = 0;

			$flattened[$category->id] = $category;

			foreach($category->children as $child)
			{
				$child->remaining = 0;

				$flattened[$child->id] = $child;
			}
		}

		// We'll now get an array of discussions ordering it by the last reply ID, as those with a higher
		// ID are those that have been posted in recently. Then we need to sort them by their category ID.
		$discussions = Discussions::with(array('author', 'recent'))->where_in('category_id', array_keys($flattened))->order_by('last_reply_id', 'desc')->get();

		$filtered = array();

		// This is an admin configurable option where they can set how many discussions to show per category
		// on the overview page.
		$allowed = C::get('voicify.overview.discussions_per_category');

		foreach($discussions as $key => $discussion)
		{
			$discussion->category = $flattened[$discussion->category_id];

			// If the discussions category is a child then we need to use it's parent ID.
			$id = $discussion->category->child ? $discussion->category->parent_id : $discussion->category->id;

			// If the parent category ID is not in the filtered array or we have less then the allowed
			// amount then we can add another discussion to show. If not we'll increment the categories
			// remaining counter.
			if(!array_key_exists($id, $filtered) or count($filtered[$id]) < $allowed)
			{
				$filtered[$id][] = $discussion;
			}
			else
			{
				$discussion->category->remaining++;
			}
		}

		// Now we can loop over each of the parent categories and add the discussions to them.
		foreach($categories as $category)
		{
			$category->discussions = array_key_exists($category->id, $filtered) ? $filtered[$category->id] : array();
		}

		return $categories;
	}

}