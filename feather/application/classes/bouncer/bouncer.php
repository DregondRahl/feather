<?php namespace Bouncer;

use Auth;
use Config;

class Bouncer extends Auth {

	public static function is($role)
	{
		$roles = Config::get('bouncer.permissions');

		if(!array_key_exists($role, $roles))
		{
			return false;
		}

		$ids = $roles[$role];

		foreach(static::user()->roles as $role)
		{
			if(in_array($role->id, $ids))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if the user is online and returns the user object.
	 * 
	 * @return object
	 */
	public static function online()
	{
		return Auth::check();
	}

	/**
	 * Checks if the user is online and is activated.
	 * 
	 * @return bool
	 */
	public static function activated()
	{
		if($user = static::user())
		{
			return ($user->activated == 1);
		}

		return false;
	}
}