<?php namespace Service;

use Auth;

class Security {

	/**
	 * Attempt to log a user into their account depending on the argument given to the method.
	 * 
	 * @param  array|object  $what
	 * @return bool|object
	 */
	public static function authorize($what)
	{
		// If the argument is an instance of a user repository then we can just log the user straight in
		// without having to give the username and password.
		if($what instanceof Repository\User)
		{
			return Auth::login($what);
		}
		// If the argument is an array we'll assume that it's the proper credentials needed to log a user
		// into their account.
		elseif(is_array($what))
		{
			return Auth::attempt($what);
		}

		return false;
	}

	/**
	 * Returns the current logged in user.
	 * 
	 * @return object
	 */
	public static function user()
	{
		return Auth::user();
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

	/**
	 * Log a user out of Feather.
	 * 
	 * @return void
	 */
	public static function logout()
	{
		Auth::logout();
	}

}