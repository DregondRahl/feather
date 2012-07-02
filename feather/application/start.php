<?php

/*
|--------------------------------------------------------------------------
| PHP Display Errors Configuration
|--------------------------------------------------------------------------
|
| Since Laravel intercepts and displays all errors with a detailed stack
| trace, we can turn off the display_errors ini directive. However, you
| may want to enable this option if you ever run into a dreaded white
| screen of death, as it can provide some clues.
|
*/

ini_set('display_errors', 'On');

/*
|--------------------------------------------------------------------------
| Laravel Configuration Loader
|--------------------------------------------------------------------------
|
| The Laravel configuration loader is responsible for returning an array
| of configuration options for a given bundle and file. By default, we
| use the files provided with Laravel; however, you are free to use
| your own storage mechanism for configuration arrays.
|
*/

Laravel\Event::listen(Laravel\Config::loader, function($bundle, $file)
{
	if(file_exists($path = path('config') . $file . EXT))
	{
		return require path('config') . $file . EXT;
	}

	return Laravel\Config::file($bundle, $file);
});

/*
|--------------------------------------------------------------------------
| Register Class Aliases
|--------------------------------------------------------------------------
|
| Aliases allow you to use classes without always specifying their fully
| namespaced path. This is convenient for working with any library that
| makes a heavy use of namespace for class organization. Here we will
| simply register the configured class aliases.
|
*/

$aliases = Laravel\Config::get('application.aliases');

Laravel\Autoloader::$aliases = $aliases;

require __DIR__ . DS . 'start' . DS . 'autoloading' . EXT;

/*
|--------------------------------------------------------------------------
| Laravel View Loader
|--------------------------------------------------------------------------
|
| The Laravel view loader is responsible for returning the full file path
| for the given bundle and view. Of course, a default implementation is
| provided to load views according to typical Laravel conventions but
| you may change this to customize how your views are organized.
|
*/

Event::listen(View::loader, function($bundle, $view)
{
	$path = Bundle::path($bundle) . 'views';

	if(!is_null(View::file($bundle, $view, path('theme') . 'views')))
	{
		$path = path('theme') . 'views';
	}

	return View::file($bundle, $view, $path);
});

/*
|--------------------------------------------------------------------------
| Laravel Language Loader
|--------------------------------------------------------------------------
|
| The Laravel language loader is responsible for returning the array of
| language lines for a given bundle, language, and "file". A default
| implementation has been provided which uses the default language
| directories included with Laravel.
|
*/

Event::listen(Lang::loader, function($bundle, $language, $file)
{
	return Lang::file($bundle, $language, $file);
});

/*
|--------------------------------------------------------------------------
| Attach The Laravel Profiler
|--------------------------------------------------------------------------
|
| If the profiler is enabled, we will attach it to the Laravel events
| for both queries and logs. This allows the profiler to intercept
| any of the queries or logs performed by the application.
|
*/

if (Config::get('application.profiler'))
{
	Profiler::attach();
}

/*
|--------------------------------------------------------------------------
| Enable The Blade View Engine
|--------------------------------------------------------------------------
|
| The Blade view engine provides a clean, beautiful templating language
| for your application, including syntax for echoing data and all of
| the typical PHP control structures. We'll simply enable it here.
|
*/

Blade::sharpen();

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| We need to set the default timezone for the application. This controls
| the timezone that will be used by any of the date methods and classes
| utilized by Laravel or your application. The timezone may be set in
| your application configuration file.
|
*/

date_default_timezone_set(Config::get('application.timezone'));

/*
|--------------------------------------------------------------------------
| Start / Load The User Session
|--------------------------------------------------------------------------
|
| Sessions allow the web, which is stateless, to simulate state. In other
| words, sessions allow you to store information about the current user
| and state of your application. Here we'll just fire up the session
| if a session driver has been configured.
|
*/

if ( ! Request::cli() and Config::get('session.driver') !== '')
{
	Session::load();
}

/*
|--------------------------------------------------------------------------
| Define Paths
|--------------------------------------------------------------------------
|
| Define a few extra useful paths for Velvet.
|
*/
set_path('theme', path('public') . 'themes/' . Config::get('feather.application.theme', 'default') . '/');

/*
|--------------------------------------------------------------------------
| Feather Configuration
|--------------------------------------------------------------------------
|
| Update some configurations such as the database connection prior to
| starting the application. Once database config is updated we can fetch 
| the configuration from the database and register any bundles.
|
*/
Config::set('database.connections.default', Config::get('config'));

Config::database() and Config::bundles();

/*
|--------------------------------------------------------------------------
| Bouncer Authentication
|--------------------------------------------------------------------------
|
| Register the Bouncer authentication package which is used to authorize
| users within Feather.
|
*/
Auth::extend('bouncer', function()
{
	return new Bouncer\Driver;
});

/*
|--------------------------------------------------------------------------
| Startup
|--------------------------------------------------------------------------
|
| Load in the required start files to begin Feather.
|
*/
require __DIR__ . DS . 'start' . DS . 'events' . EXT;

require __DIR__ . DS . 'start' . DS . 'filters' . EXT;

require __DIR__ . DS . 'start' . DS . 'composers' . EXT;

require __DIR__ . DS . 'start' . DS . 'validators' . EXT;

if(file_exists($path = path('theme') . 'start'))
{
	foreach(new FilesystemIterator($path) as $starter)
	{
		require $starter->getPathname();
	}
}