<?php

Route::get('category/(:any)', 'category@category');

Route::any('signin', array('as' => 'login', 'uses' => 'home@signin'));
Route::any('join', array('as' => 'register', 'uses' => 'home@join'));
Route::get('signout', array('as' => 'logout', 'uses' => 'home@signout'));

Route::get('rules', array('as' => 'rules', 'uses' => 'home@rules'));
Route::get('confirmation/resend', array('as' => 'resend', 'uses' => 'confirmation@resend'));

Route::filter('pattern: *', array('name' => 'admin.auth:hello', function($param = null)
{
	dd($param);
	die("YAHHOO");
}));

Route::controller(array(
	'home',
	'category'
));
