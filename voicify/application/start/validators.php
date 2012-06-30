<?php

/**
 * Validate that a value only contains letters, numbers and periods. It cannot end in a period.
 */
Validator::register('alpha_dot', function($attribute, $value)
{
	return preg_match('/^([a-z0-9\.])+$/i', $value) ? (ends_with($value, '.') ? false : true) : false;
});

/**
 * Validate a recaptcha to ensure it was enterted correctly.
 */
Validator::register('recaptcha', function($attribute, $value, $parameters)
{
	$private = Config::get('feather.registration.recaptcha_private_key');
	$recaptcha = Recaptcha::recaptcha_check_answer($private, Request::ip(), Input::get('recaptcha_challenge_field'), $value);

	return $recaptcha->is_valid;
});