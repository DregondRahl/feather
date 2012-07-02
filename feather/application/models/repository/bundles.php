<?php namespace Repository;

use Eloquent;

class Bundles extends Eloquent {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'bundles';

	/**
	 * Return all bundles that have been enabled.
	 * 
	 * @return  array
	 */
	public static function enabled()
	{
		return static::where_enabled(1)->get();
	}

}