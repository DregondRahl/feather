<?php

Route::get('category/(:any)', 'category@category');

Route::any('login', array('as' => 'login', 'uses' => 'home@login'));
Route::any('join', array('as' => 'register', 'uses' => 'home@join'));
Route::get('logout', array('as' => 'logout', 'uses' => 'home@logout'));

Route::get('rules', array('as' => 'rules', 'uses' => 'home@rules'));
Route::get('confirmation/resend', array('as' => 'resend', 'uses' => 'confirmation@resend'));

Route::controller(array(
	'home',
	'category'
));
