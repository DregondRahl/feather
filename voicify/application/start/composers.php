<?php

View::share('app', Config::get('voicify.forum'));

$users = array();

if(Service\Security::online())
{
	$users['online'] = Service\Security::user();
}

View::share('users', (object) $users);