<?php

class Oak extends Eloquent {

	protected $position;

	protected $leaf = false;

	/**
	 * Set the position of the node.
	 * 
	 * @param  int  $position
	 * @return object
	 */
	public function position($position)
	{
		$this->position = $position;

		return $this;
	}

	/**
	 * Makes the node a leaf of a branch. Branch can either be the ID or an
	 * Oak object.
	 * 
	 * @param  object|int  $node
	 * @return bool
	 */
	public function leaf($node)
	{
		$this->leaf = true;

		if(!$node instanceof Oak)
		{
			$node = static::find($node);
		}

		// To make room for this leaf on the parent node we have to perform an adjustment
		// based on the left position of our new node.
		$adjuster = $node->is_parent() ? 'lft' : 'rgt';

		// To determine the position we can count how many children nodes there are by
		// getting the difference between the parent nodes left and right values then
		// dividing by two and rounding up.
		$children = floor(($node->rgt - $node->lft) / 2);

		if($this->position < 1 or $this->position > $children)
		{
			$this->position = $children + 1;
		}

		$this->fill(array(
			'lft' => $node->lft + ($this->position * 2) - 1,
			'rgt' => $node->lft + ($this->position * 2)
		));

		$this->adjust($this->lft);

		return $this;
	}

	/**
	 * Default action applied to nodes that aren't leaves.
	 * 
	 * @return void
	 */
	protected function branch()
	
{		if(!$node = static::select(array('id', 'lft', 'rgt'))->order_by('rgt', 'desc')->first())
		{
			$node = (object) array(
				'lft'      => 0,
				'rgt'	   => 0
			);
		}

		$this->fill(array(
			'lft' => $node->rgt + 1,
			'rgt' => $node->rgt + 2
		));
	}

	/**
	 * Refreshes the current node, loading any updated attributes.
	 * 
	 * @return object
	 */
	public function refresh()
	{
		if($this->exists)
		{
			$updated = static::find($this->id);

			$this->fill($updated->attributes);
		}

		return $this;
	}

	public function children($depth = 1)
	{
		
	}

	public function before($token)
	{

	}

	public function after($token)
	{

	}

	/**
	 * Checks if the node is a parent.
	 * 
	 * @return bool
	 */
	protected function is_parent()
	{
		return ($this->rgt - $this->lft) > 1;
	}

	/**
	 * Perform an adjustment of the nodes.
	 * 
	 * @param  int  $value
	 * @return void
	 */
	protected function adjust($value)
	{
		static::where('lft', '>=', $value)->update(array('lft' => DB::raw('lft + 2')));

		static::where('rgt', '>=', $value)->update(array('rgt' => DB::raw('rgt + 2')));
	}

	/**
	 * Overload the save method to unset the position property.
	 * 
	 * @return bool
	 */
	public function save()
	{
		if(!$this->exists and !$this->leaf)
		{
			$this->branch();
		}

		unset($this->attributes['position']);

		return parent::save();
	}

}