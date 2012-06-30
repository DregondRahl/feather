<?php namespace Bouncer\Migration\Drivers;

use Bouncer\Migration\Drivers\Driver;

class Flux extends Driver {

	/**
	 * Attempt to log a user in on the FluxBB system.
	 * 
	 * @param  array  $credentials
	 * @return bool|object
	 */
	public function passes($credentials)
	{
		$old = $this->old()->where_username($credentials['username'])->first();

		if(is_null($old) or sha1($credentials['password']) != $old->password)
		{
			return false;
		}

		return $old;
	}

	public function migrate()
	{
		
	}

}