<?php namespace Repository;

use Eloquent;

class Migrations extends Eloquent {

	public static $table = 'migrations';

	/**
	 * Return the first active migration.
	 * 
	 * @return object
	 */
	public static function active()
	{
		return static::where_active(1)->first();
	}

}