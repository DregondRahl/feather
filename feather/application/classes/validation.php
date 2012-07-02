<?php

abstract class Validation {

	/**
	 * An array of rules to validate the form data against.
	 * 
	 * @var array
	 */
	protected static $rules = array();

	/**
	 * An array of corrosponding error messages.
	 * 
	 * @var array
	 */
	protected static $messages = array();

	/**
	 * An array of input to be validated.
	 * 
	 * @var array
	 */
	protected static $input = array();

	/**
	 * The Validator object once validation has either passes or failed.
	 * 
	 * @var object
	 */
	public static $validator;

	/**
	 * Validates a form and returns if the form has passed validation. The Validator object will
	 * be made available on the validator property.
	 * 
	 * @param   array  $input
	 * @return  bool
	 */
	public static function passes($input)
	{
		static::$input = $input;

		static::before();

		// Reconstruct the messages array into the format that Laravel accepts. This is so
		// the messages provided in the model are legible.
		$messages = array();

		foreach(static::$messages as $field => $rules)
		{
			foreach($rules as $rule => $message)
			{
				$replacements = array();

				if(is_array($message))
				{
					list($message, $replacements) = $message;
				}

				$messages[$field . '_' . $rule] = __($message, $replacements)->get();
			}
		}

		static::$validator = new Validator(static::$input, static::$rules, $messages);

		return static::$validator->passes();
	}

	/**
	 * Provide a place for rules to be altered based on the input that is being validated.
	 * 
	 * @return  array
	 */
	public static function before(){}

	/**
	 * Provide quicker access to the errors of the validated form.
	 * 
	 * @throws  Exception
	 * @return  Laravel\Messages
	 */
	public static function errors()
	{
		if(static::$validator instanceof Validator)
		{
			return static::$validator->errors;
		}

		throw new Exception('Could not fetch errors, make sure form is validated first.');
	}

}