<?php

class Date {

	/**
	 * Array of the fuzzy dates periods and lengths.
	 * 
	 * @var array
	 */
	protected static $fuzzy = array(
		'periods' => array('second', 'minute', 'hour', 'day', 'week', 'month', 'year'),
		'lengths' => array(60, 60, 24, 7, 4.35, 12)
	);

	/**
	 * The DateTime being used for the current instance.
	 * 
	 * @var object
	 */
	protected $date;

	/**
	 * Creates a new chainable date instance.
	 * 
	 * @param  string|int  $date
	 * @return object
	 */
	public static function make($date)
	{
		return new static($date);
	}

	/**
	 * Format a date and return it for the discussion meta data.
	 * 
	 * @param  string|int  $date
	 * @return string
	 */
	public static function meta($date)
	{
		$date = new static($date);

		return '<span title="' . $date->format('long') . '">' . $date->relative('short') . '</span>';
	}
	
	/**
	 * Create a new Date instance.
	 * 
	 * @param  string|int  $date
	 * @return void
	 */
	public function __construct($date = null)
	{
		$this->date = new DateTime;

		if(is_null($date))
		{
			$this->date->setTimestamp(time());
		}
		elseif(is_numeric($date))
		{
			$this->date->setTimestamp($date);
		}
		else
		{
			$this->date->setTimestamp(strtotime($date));
		}
	}
	
	/**
	 * Return the actual time integer.
	 * 
	 * @return int
	 */
	public function time()
	{
		return $this->time;
	}
	
	/**
	 * Formats the time and returns the formatted date.
	 * 
	 * @param  string  $format
	 * @return string
	 */
	public function format($format)
	{
		$formats = array(
			'long'  => Config::get('voicify.datetime.long_date'),
			'short' => Config::get('voicify.datetime.short_date'),
			'time'  => Config::get('voicify.datetime.time_only')
		);

		if(array_key_exists($format, $formats))
		{
			$format = $formats[$format];
		}

		return $this->date->format($format);
	}
	
	/**
	 * Returns a fuzzy formated date.
	 * 
	 * @return string
	 */
	public function fuzzy($stop = 'week', $format = 'long')
	{
		$difference = time() - $this->date->getTimestamp();

		$offset = array_search($stop, static::$fuzzy['periods']) + 1;

		$periods = array_slice(static::$fuzzy['periods'], 0, $offset);

		$lengths = array_slice(static::$fuzzy['lengths'], 0, $offset);
		
		for($i = 0; $difference >= $lengths[$i] and $i < count($lengths) - 1; $i++)
		{
			$difference = $difference / $lengths[$i];
		}
		
		$difference = round($difference);
		
		if($difference != 1)
		{
			$periods[$i] .= 's';
		}

		if($difference > $lengths[$i])
		{
			return $this->format($format);
		}

		return number_format($difference) . ' ' . $periods[$i] . ' ago';
	}

	/**
	 * Returns a relative formated date.
	 * 
	 * @return string
	 */
	public function relative($format = 'long')
	{
		$days = intval((time() - $this->date->getTimestamp()) / 86400);

		if($days == 0)
		{
			return __('date.today')->get() . ', ' . $this->format('time');
		}
		elseif($days == 1)
		{
			return __('date.yesterday')->get() . ', ' . $this->format('time');
		}

		return $this->format($format);
	}
	
}