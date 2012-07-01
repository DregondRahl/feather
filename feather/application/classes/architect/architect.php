<?php namespace Architect;

class Architect {

	/**
	 * An array of results to be used by the Architect.
	 * 
	 * @var array
	 */
	protected $results;

	/**
	 * Creates a new Architect instance.
	 * 
	 * @param  array  $results
	 * @return void
	 */
	public function __construct($results)
	{
		$this->results = $results;
	}

	/**
	 * Fetch the results from the database.
	 * 
	 * @return object
	 */
	protected function get()
	{
		$this->results = $this->results->get();

		return $this;
	}

	/**
	 * Magic method for returning results without always having to call the get method.
	 * 
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if(in_array($method, array('html', 'array')))
		{
			$this->get();

			$generate = $this->generate($this->results);

			switch($method)
			{
				case 'array':
					return $generate;
			}
		}
	}

}