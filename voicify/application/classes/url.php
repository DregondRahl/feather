<?php

class URL extends Laravel\URL {

	/**
	 * Generate a URL to the theme.
	 * 
	 * @return string
	 */
	public static function theme()
	{
		return static::base() . '/themes/' . Config::get('feather.application.theme') . '/';
	}

}