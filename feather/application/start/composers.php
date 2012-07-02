<?php

$users = array();

if(Bouncer::online())
{
	$users['online'] = Bouncer::user();
}

View::share('app', (object) array_merge(Config::get('feather.forum'), array('users' => (object) $users)));

unset($users);