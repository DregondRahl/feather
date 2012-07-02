<?php namespace Repository;

use Str;
use Hash;
use Eloquent;
use Config as C;

class User extends Eloquent {

	/**
	 * The table name.
	 * 
	 * @var string
	 */
	public static $table = 'users';

	/**
	 * Timestamps are enabled.
	 * 
	 * @var bool
	 */
	public static $timestamps = true;

	/**
	 * Register a new user and return the new user object.
	 * 
	 * @param   array   $input
	 * @return  object
	 */
	public static function register($input)
	{
		$user = new static;

		$user->username = $input['username'];
		$user->password = $input['password'];
		$user->email = $input['email'];

		if(C::get('feather.registration.confirm_email'))
		{
			$user->activation_key = Str::random(30);
			$user->activated = 0;
		}

		$user->save();

		// Attach a role to the user depending on whether they need to confirm
		// their e-mail address.
		$user->roles()->attach(C::get('feather.registration.confirm_email') ? 3 : 2);

		return $user;
	}

	/**
	 * A user can have many and belong to many roles.
	 * 
	 * @return object
	 */
	public function roles()
	{
		return $this->has_many_and_belongs_to('Repository\\Roles', 'user_roles', 'user_id', 'role_id');
	}

	/**
	 * When setting a password it must be hashed before stored in the database.
	 * 
	 * @param  string  $password
	 */
	public function set_password($password)
	{
		$this->set_attribute('password', Hash::make($password));
	}

	/**
	 * Getter for a users slug.
	 * 
	 * @return string
	 */
	public function get_slug()
	{
		return Str::slug($this->get_attribute('username'));
	}

}