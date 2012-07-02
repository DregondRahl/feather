<?php namespace Validation;

use Config;
use Validation as Validation_Service;

class Register extends Validation_Service {

	/**
	 * Rules.
	 */
	protected static $rules = array(
		'username'				   => array('required', 'min:3', 'max:20', 'alpha_dot', 'unique:users,username'),
		'password' 				   => array('required', 'min:7', 'max:15', 'confirmed'),
		'password_confirmation'	   => array('required'),
		'email'	   				   => array('required', 'email', 'unique:users,email'),
		'recaptcha_response_field' => array('required', 'recaptcha')
	);

	/**
	 * Error messages.
	 */
	protected static $messages = array(
		'username' => array(
			'required'  => 'register.username.is_required',
			'min'	    => array('register.username.too_short', array('length' => 3)),
			'max'	    => array('register.username.too_long', array('length' => 20)),
			'alpha_dot' => 'register.username.is_invalid',
			'unique'	=> 'register.username.already_exists'
		),
		'password' => array(
			'required'  => 'register.password.is_required',
			'min'	    => array('register.password.too_short', array('length' => 7)),
			'max'	    => array('register.password.too_long', array('length' => 15)),
			'confirmed' => 'register.password.did_not_match'
		),
		'password_confirmation' => array(
			'required' => 'register.password_confirmation.is_required'
		),
		'email' => array(
			'required' => 'register.email.is_required',
			'email'	   => 'register.email.invalid',
			'unique'   => 'register.email.already_exists'
		),
		'recaptcha_response_field' => array(
			'required'	=> 'register.recaptcha.is_required',
			'recaptcha' => 'register.recaptcha.is_incorrect'
		)
	);

	/**
	 * Adjust the rules.
	 */
	public static function before()
	{
		if(Config::get('feather.registration.rules'))
		{
			static::$rules['rules'] = array('accepted');
			static::$messages['rules']['accepted'] = 'register.rules.not_accepted';
		}
	}

}