<?php namespace Bouncer\Migration\Drivers;

use DB;
use Config;

abstract class Driver {

	/**
	 * Array of drivers that are extending the default drivers.
	 */
	protected static $extenders = array();

	/**
	 * Driver that is to be used by migrations.
	 */
	private static $driver;

	/**
	 * Database connection used by the migration.
	 */
	private $connection;

	/**
	 * Format that dates are stored in.
	 */
	public $datetime = 'Y-m-d H:i:s';

	/**
	 * Creates a new migration driver instance.
	 * 
	 * @param  object  $connection
	 * @return void
	 */
	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Attaches a driver for the current migration and returns a new instance of the driver.
	 * 
	 * @param  string  $driver
	 * @return object
	 */
	public static function attach($migration)
	{
		$connection = static::connection($migration);

		if(isset($extenders[$migration->driver]))
		{
			$resolver = $extenders[$migration->driver];

			return static::$driver = $resolver($connection);
		}

		switch($migration->driver)
		{
			case 'flux':
				return static::$driver = new Flux($connection);
		}
	}

	/**
	 * Connect to the database and return a Query object with the table.
	 * 
	 * @param  object  $migration
	 * @return object
	 */
	protected static function connection($migration)
	{
		// Assume the default connection by default, we'll change to the migration
		// connection next if it actually exists. This is useful if the tables still exist
		// on the same database.
		$connection = DB::connection();

		if(!is_null($migration->database))
		{
			Config::set('database.connections.migration', array(
				'driver'   => 'mysql',
				'host'     => $migration->host,
				'database' => $migration->database,
				'username' => $migration->username,
				'password' => $migration->password,
				'charset'  => 'utf8',
				'prefix'   => ''
			));

			$connection = DB::connection('migration');
		}

		return $connection->table($migration->table);
	}

	/**
	 * Register a custom migration driver.
	 * 
	 * @param  string   $driver
	 * @param  closure  $resolver
	 * @return void
	 */
	public static function extend($driver, $resolver)
	{
		static::$extenders[$driver] = $resolver;
	}

	/**
	 * Attempt to log a user in on the old system.
	 * 
	 * @param  array  $credentials
	 * @return bool|object
	 */
	abstract public function login($credentials);

	/**
	 * Migrates topics, replies, users, and personal conversations from the
	 * old system to Feather. This is generally a heavy duty method.
	 * 
	 * @return bool
	 */
	abstract public function migrate();

	/**
	 * Migrate a user from the old system to Feather.
	 * 
	 * @param  object  $old
	 * @param  object  $user
	 * @return bool
	 */
	public function migrate_user($old, $user)
	{
		// Passwords are in the unhashe
		$user->password = $old->password;

		if(!$user->save())
		{
			return false;
		}

		// Sync only the member role to the new user.
		$user->roles()->sync(array(3));

		return $user;
	}

	/**
	 * Return the old system database connection.
	 * 
	 * @return object
	 */
	public function old()
	{
		return $this->connection;
	}

	/**
	 * Magic method for calling methods on a migration driver.
	 */
	public static function __callStatic($method, $parameters)
	{
		$result = call_user_func_array(array(static::$driver, $method), $parameters);
		
		// If a migration has been successfull we will log it so administrators can keep track of what
		// has been migrated.
		if($result and in_array($method, array('user', 'topic', 'reply')))
		{
			$this->log($method, $parameters);
		}

		return $result;
	}

}