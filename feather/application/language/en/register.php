<?php

return array(
	'failed' => 'Something unfortunate has happened and we could not complete the process. Please try again or let us know you are having problems.',
	'username' => array(
		'is_required'    => 'You did not enter a username.',
		'too_short'      => 'Your username must be at least :length characters.',
		'too_long'	     => 'Your username must be less than :length characters.',
		'is_invalid'     => 'Your username contained invalid characters.',
		'already_exists' => 'An account already exists with that username.'
	),
	'password' => array(
		'is_required'   => 'You did not enter a password.',
		'too_short'	    => 'Your password must be at least :length characters.',
		'too_long'	    => 'Your password must be less than :length characters.',
		'did_not_match' => 'The passwords you entered did not match.',
	),
	'password_confirmation' => array(
		'is_required'   => 'You did not confirm your password.',
	),
	'email' => array(
		'is_required'    => 'You did not enter an e-mail address.',
		'invalid'	     => 'The e-mail you entered is invalid.',
		'already_exists' => 'An account already exists with that e-mail.'
	),
	'recaptcha' => array(
		'is_required'  => 'You did not enter the security confirmation.',
		'is_incorrect' => 'The security confirmation could not be matched.'
	),
	'rules' => array(
		'not_accepted' => 'You must read and accept the community rules.'
	)
);