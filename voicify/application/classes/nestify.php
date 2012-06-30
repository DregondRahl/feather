<?php

/**
 * Nestify is a Nested Sets Modal based on the Nesty bundle released by the Cartalyst team. Nestify is
 * aimed at being slightly less bulky while still providing a great feature set. Be sure to check out
 * Nesty if you want more customizing: <https://github.com/cartalyst/nesty>
 * 
 * @author  Jason Lewis
 * @version 1.0.0
 */
class Nestify extends Eloquent {

	/**
	 * Place a node after another node in the tree.
	 * 
	 * @param  object|int  $node
	 * @return object
	 */
	public function after($node)
	{
		return $this->sibling($node, 'after');
	}

	/**
	 * Place a node before another node in the tree.
	 * 
	 * @param  object|int  $node
	 * @return object
	 */
	public function before($node)
	{
		return $this->sibling($node, 'before');
	}

	/**
	 * Makes a node a sibling of another node, the position can either be before or after.
	 * 
	 * @param  object|int  $node
	 * @param  string      $position
	 * @return object
	 */
	protected function sibling($node, $position)
	{
		$node = $this->check($node);

		// If our node currently exists we need to fake its death, shifting it outside of the
		// tree momenterily.
		if($this->exists)
		{
			$this->fake();

			// Refresh the node we are placing it after so we can get the latest right value.
			$node->refresh();

			// And now revive the bastard to the new left position.
			$this->revive(($position == 'after') ? $node->rgt + 1 : $node->lft);
		}

		// If the node doesn't exist yet in the tree we need to set the left and right values.
		// If our position is BEFORE our left value will be the siblings current left value, if
		// it is AFTER then it will be the right value plus 1. The right value will always be
		// the left value plus 1.
		else
		{
			$this->lft = ($position == 'after') ? $node->rgt + 1 : $node->lft;

			$this->rgt = $this->lft + 1;

			// We need to make an adjustment to the tree so we can fit out brand new node in.
			// Once done we'll save our new node.
			$this->adjustment($this->lft)
				 ->save();
		}

		return $this;
	}

	/**
	 * Refreshes the node by getting the latest left and right values from the database.
	 * 
	 * @return object
	 */
	public function refresh()
	{
		$updated = static::find($this->id);

		$this->fill(array(
			'lft' => $updated->lft,
			'rgt' => $updated->rgt
		));

		return $this;
	}

	/**
	 * Fakes the removal of the node and its children from the database so that a node
	 * can be shifted around.
	 * 
	 * @return object
	 */
	protected function fake()
	{
		// To fake the removal we'll shift the node outside of the tree by subtracting the
		// right value from the node and its children.
		static::where('lft', 'BETWEEN', DB::raw("{$this->lft} AND {$this->rgt}"))->update(array(
			'lft' => DB::raw("lft - {$this->rgt}"),
			'rgt' => DB::raw("rgt - {$this->rgt}")
		));

		// Adjust all those from our current left value by reducing a gap. Any nodes that have
		// left and right values greater than or equal to this node will have 2 values taken
		// from both their left and right values, thus 'removing' this node from the tree.
		return $this->adjustment($this->lft, ($this->width() + 1) * -1);
	}

	/**
	 * Revives a fake death (weird) node and all its children. Hallelujah!
	 * 
	 * @param  int  $on
	 * @return object
	 */
	protected function revive($on)
	{
		// Make the adjustment to fit the node back into the tree.
		$this->adjustment($on);

		// To revive the faked death we need to shift the node back into the tree by adding the
		// left value and width of the node to those nodes between the negative width and zero.
		$left = $this->width() * -1;

		$adjustment = $on + $this->width();

		static::where('lft', 'BETWEEN', DB::raw("{$left} AND 0"))->update(array(
			'lft' => DB::raw("lft + {$adjustment}"),
			'rgt' => DB::raw("rgt + {$adjustment}")
		));

		return $this;
	}

	/**
	 * Perform an adjustment of all nodes from a given amount and of a given width.
	 * 
	 * @param  int  $from
	 * @param  int  $width
	 * @return object
	 */
	protected function adjustment($from, $width = null)
	{
		if(is_null($width))
		{
			$width = $this->width() + 1;
		}

		static::where('lft', '>=', $from)->update(array('lft' => DB::raw("lft + {$width}")));

		static::where('rgt', '>=', $from)->update(array('rgt' => DB::raw("rgt + {$width}")));

		return $this;
	}

	/**
	 * Returns the width of the node in the tree.
	 * 
	 * @return int
	 */
	protected function width()
	{
		return $this->rgt - $this->lft;
	}

	/**
	 * Checks a node to make sure it's valid, if not attempts to make it valid.
	 * 
	 * @param  object|int  $node
	 * @return object
	 */
	protected function check($node)
	{
		if(!$node instanceof Repository\Category)
		{
			$node = static::find($node);
		}

		return $node;
	}

}