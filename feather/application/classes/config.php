<?php

class Config extends Laravel\Config {

	/**
	 * Load the config from the database and cache it for a lifetime. The cache
	 * is only destroyed when the config is updated.
	 * 
	 * @return  void
	 */
	public static function database()
	{
		Cache::forget('config');

		return static::$items['application']['feather'] = Cache::sear('config', function()
		{
			$config = array();

			foreach(Repository\Config::all() as $item)
			{
				array_set($config, $item->attributes['key'], $item->attributes['value']);
			}

			return $config;
		});
	}

	/**
	 * Loads and registers the bundles from the database and caches it for a lifetime.
	 * The cache is the same as the config cache above.
	 * 
	 * @return  void
	 */
	public static function bundles()
	{
		Cache::forget('bundles');

		return Cache::sear('bundles', function()
		{
			foreach(Repository\Bundles::enabled() as $bundle)
			{
				Bundle::register($bundle->identifier, array(
					'handles' 	=> $bundle->handles,
					'auto'	  	=> (bool) $bundle->auto,
					'autoloads' => (array) json_decode($bundle->autoloads)
				));
			}
		});
	}

}