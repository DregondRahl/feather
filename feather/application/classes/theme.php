<?php

class Theme {

	/**
	 * Returns a link to a stylesheet within the current theme.
	 * 
	 * @param  string  $url
	 * @return string
	 */
	public static function style($url)
	{
		return HTML::style(URL::theme() . $url);
	}

	/**
	 * Returns a link to a script within the current theme.
	 * 
	 * @param  string  $url
	 * @return string
	 */
	public static function script($url)
	{
		return HTML::script(URL::theme() . $url);
	}
	
}