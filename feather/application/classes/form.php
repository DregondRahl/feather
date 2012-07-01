<?php

class Form extends Laravel\Form {

	/**
	 * A Laravel\Messages object containing the error messages.
	 */
	protected static $errors;

	/**
	 * Register the errors object with the form so errors can be easily accessed.
	 * 
	 * @param   object  $errors
	 * @return  void
	 */
	public static function errors($errors)
	{
		static::$errors = $errors;

		return count($errors->all()) == 0 ? null : view('theme: error.form', compact($errors));
	}

	/**
	 * Overload the input method to allow for error reporting.
	 * 
	 * @param   string  $type
	 * @param   string  $name
	 * @param   string  $value
	 * @param   array   $attributes
	 * @return  string
	 */
	public static function input($type, $name, $value = null, $attributes = array())
	{
		$element = parent::input($type, $name, $value, $attributes);

		if(!in_array($type, array('submit', 'button')) and static::$errors->has($name))
		{
			return static::error($name, $element);
		}

		return $element;
	}

	/**
	 * Wraps an input in an inline error class and displays the first error found
	 * on the element.
	 * 
	 * @param   string  $name
	 * @param   string  $element
	 * @return  string
	 */
	protected static function error($name, $element)
	{
		return '<span class="error">' . $element . '</span>';
	}

}