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

	/**
	 * Getter for the replies. Turns it into a readable format.
	 * 
	 * @return string
	 */
	public function get_short_replies()
	{
		return $this->humanize($this->get_attribute('replies'));
	}

	/**
	 * Getter for the views. Turns it into a readable format.
	 * 
	 * @return string
	 */
	public function get_short_views()
	{
		return $this->humanize($this->get_attribute('views'));
	}

	/**
	 * Humanize the replies and views numbers.
	 * 
	 * @param  int  $number
	 * @return string
	 */
	protected function humanize($number)
	{
		// Oh wow, under a 1,000... Not bad.
		if($number < 1000)
		{
			$number = number_format($number);
		}
		// Hold up, getting up in the thousands!
		elseif($number < 1000000)
		{
			$number = number_format($number / 1000, 1) . 'K';
		}
		// Ah-hoy! A million! We won't worry about a billion, seems like too many.
		else
		{
			$number = number_format($number / 1000000, 1) . 'M';
		}

		return str_replace('.0', '', $number);
	}

}