<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @version  3.2.0 Beta 1
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 * @link     http://laravel.com
 */

// --------------------------------------------------------------
// Tick... Tock... Tick... Tock...
// --------------------------------------------------------------
define('LARAVEL_START', microtime(true));

// --------------------------------------------------------------
// Indicate that the request is from the web.
// --------------------------------------------------------------
$web = true;

// --------------------------------------------------------------
// Set the core Laravel path constants.
// --------------------------------------------------------------
$environments = array();

// --------------------------------------------------------------
// The path to the application directory.
// --------------------------------------------------------------
$paths['app'] = '../feather/application';

// --------------------------------------------------------------
// The path to the Laravel directory.
// --------------------------------------------------------------
$paths['sys'] = '../feather/laravel';

// --------------------------------------------------------------
// The path to the config directory.
// --------------------------------------------------------------
$paths['config'] = '../config';

// --------------------------------------------------------------
// The path to the bundles directory.
// --------------------------------------------------------------
$paths['bundle'] = '../feather/bundles';

// --------------------------------------------------------------
// The path to the storage directory.
// --------------------------------------------------------------
$paths['storage'] = '../feather/storage';

// --------------------------------------------------------------
// The path to the public directory.
// --------------------------------------------------------------
$paths['public'] = '';

// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
// END OF USER CONFIGURATION. HERE BE DRAGONS!
// *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-

// --------------------------------------------------------------
// Change to the current working directory.
// --------------------------------------------------------------
chdir(__DIR__);

// --------------------------------------------------------------
// Define the directory separator for the environment.
// --------------------------------------------------------------
if ( ! defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

// --------------------------------------------------------------
// Define the path to the base directory.
// --------------------------------------------------------------
$GLOBALS['laravel_paths']['base'] = __DIR__.DS;

// --------------------------------------------------------------
// Define each constant if it hasn't been defined.
// --------------------------------------------------------------
foreach ($paths as $name => $path)
{
	if ( ! isset($GLOBALS['laravel_paths'][$name]))
	{
		$GLOBALS['laravel_paths'][$name] = realpath($path).DS;
	}
}

/**
 * A global path helper function.
 * 
 * <code>
 *     $storage = path('storage');
 * </code>
 * 
 * @param  string  $path
 * @return string
 */
function path($path)
{
	return $GLOBALS['laravel_paths'][$path];
}

/**
 * A global path setter function.
 * 
 * @param  string  $path
 * @param  string  $value
 * @return void
 */
function set_path($path, $value)
{
	$GLOBALS['laravel_paths'][$path] = $value;
}

// --------------------------------------------------------------
// Unset the temporary web variable.
// --------------------------------------------------------------
unset($web);

// --------------------------------------------------------------
// Launch Laravel.
// --------------------------------------------------------------
require path('sys').'laravel.php';