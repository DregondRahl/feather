<?php

class Base_Controller extends Controller {

	/**
	 * All controllers are that extend this base controller are RESTful in nature.
	 */
	public $restful = true;

	/**
	 * The default layout to be used by Feather.
	 */
	public $layout = 'template';

	/**
	 * Create a new Controller instance.
	 */
	public function __construct()
	{
		// Run the parent constructor to instantiate the layout.
		parent::__construct();

		// Define a default title for the system.
		$this->layout->title = 'Index';
	}

	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

}