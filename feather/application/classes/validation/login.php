<?php namespace Validation;

use Validation as Validation_Service;

class Login extends Validation_Service {

	protected static $rules = array(
		'username' => array('required'),
		'password' => array('required')
	);

	protected static $messages = array(
		'username' => array(
			'required' => 'login.username.is_required'
		),
		'password' => array(
			'required' => 'login.password.is_required'
		)
	);

}