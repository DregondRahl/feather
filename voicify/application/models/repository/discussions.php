<?php namespace Repository;

use Str;
use Eloquent;

class Discussions extends Eloquent {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'discussions';

	/**
	 * Timestamps are enabled.
	 * 
	 * @var bool
	 */
	public static $timestamps = true;

	/**
	 * A discussion has one author.
	 *
	 * @return object
	 */
	public function author()
	{
		return $this->belongs_to('Repository\\User', 'user_id');
	}

	/**
	 * A discussion has a recent poster.
	 * 
	 * @return object
	 */
	public function recent()
	{
		return $this->belongs_to('Repository\\User', 'last_reply_user_id');
	}

	/**
	 * Getter for a discussions slug.
	 * 
	 * @return string
	 */
	public function get_slug()
	{
		return Str::slug($this->get_attribute('title'));
	}


}