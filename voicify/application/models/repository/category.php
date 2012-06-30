<?php namespace Repository;

use URI;
use Oak;

class Category extends Oak {

	public static $table = 'category';
	public static $timestamps = false;

	/**
	 * Return a category based on its URI with all discussions for that category.
	 * 
	 * @param  string  uri
	 * @return object
	 */
	public static function show($uri)
	{
		if($uri == 'all')
		{
			return (object) array(
				'name'		  => 'All Categories',
				'description' => 'Some awesome description.'
			);
		}
		else
		{
			return static::where_uri($uri)->first();
		}
	}

}