<?php namespace Bouncer;

use Laravel\Auth\Drivers\Driver;
use Repository\Migrations;
use Repository\User;
use Hash;

/**
 * Bouncer is the authentication package for Feather.
 */
class Bouncer extends Driver {

	/**
	 * Get the current user of Feather.
	 * 
	 * @param  int  $id
	 * @return object|null
	 */
	public function retrieve($id)
	{
		if(!is_null($id))
		{
			return User::find($id);
		}
	}

	/**
	 * Attempt to log a user in and if need be migrate them from an older system.
	 * 
	 * @param  array  $credentials
	 * @return bool|object
	 */
	public function attempt($credentials = array())
	{
		// Bouncer needs to check for forum migrations. If there is an active forum migration we'll
		// hand off to the migration quickly to see if this user was part of the old system.
		if($migration = Migrations::active())
		{
			$driver = Migration\Drivers\Driver::attach($migration);

			$user = User::where_username($credentials['username'])->first();

			if($user->roles()->only('roles.id') == 4 and ($old = $driver->login($credentials)))
			{
				// Replace the hashed password in the old system object with the now correct unhashed password
				// so that it can be rehashed for Feather.
				$old = (object) array_merge((array) $old, array('password' => $credentials['password']));

				// Log all failed migrations so that they can be reviewed later by an administrator.
				if(!$user = $driver->migrate_user($old, $driver->user()->where_username($credentials['username'])->first()))
				{
					// TODO: The actual logging part.
					dd('Failed to migrate user.');

					return false;
				}

				// We can now continue on to the normal login process below. Thankyou for migrating with Bouncer.
			}
		}
		else
		{
			extract($credentials);

			// If Bouncer is unable to find the user then we can assume that they have entered the wrong username
			// or if the password hash fails then they entered the wrong password.
			if((!$user = User::where_username($username)->first()) or !Hash::check($password, $user->password))
			{
				return false;
			}
		}
		
		return $this->login($user->id, array_get($credentials, 'remember'));
	}
}