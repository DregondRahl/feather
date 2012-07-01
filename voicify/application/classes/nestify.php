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
	 * If we are currently deleting a node.
	 * 
	 * @var bool
	 */
	protected $deleting = false;

	/**
	 * Place a node after another node in the tree.
	 * 
	 * <code>
	 * 		$node = Nestify::find(4);
	 * 
	 * 		// Place the node after the node with an ID of 2.
	 * 		$node->after(2);
	 * 
	 * 		// Place the node after another Nestify object.
	 * 		$node->after(Nestify::find(2));
	 * </code>
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
	 * <code>
	 * 		$node = Nestify::find(4);
	 * 
	 * 		// Place the node before the node with an ID of 2.
	 * 		$node->before(2);
	 * 
	 * 		// Place the node before another Nestify object.
	 * 		$node->before(Nestify::find(2));
	 * </code>
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
	 * Nest the current node on the supplied node.
	 * 
	 * <code>
	 * 		$node = Nestify::find(3);
	 * 
	 * 		// Nest the node on node with an ID of 5.
	 * 		$node->nest(5);
	 * 
	 * 		// Nest the node on another Nestify object.
	 * 		$node->nest(Nestify::find(5));
	 * </code>
	 * 
	 * @param  object|int  $node
	 * @return object
	 */
	public function nest($node)
	{
		$node = $this->check($node);

		if(!$node->exists)
		{
			throw new Exception('The node you are nesting on has not been saved yet.');
		}

		// If our node currently exists we need to fake its death, shifting it outside of the
		// tree momenterily.
		if($this->exists)
		{
			$this->fake();

			// Refresh the node we are placing it after so we can get the latest right value.
			$node->refresh();

			// And now revive the bastard to the parent nodes right value.
			$this->revive($node->rgt);
		}

		// If the node doesn't exist yet we need set the left and right values. The new node will
		// always be placed at the end of any other children.
		else
		{
			$this->lft = $node->rgt;

			$this->rgt = $this->lft + 1;

			// We need to make an adjustment to the tree so we can fit out brand new node in.
			// Once done we'll save our new node.
			$this->adjustment($this->lft)
				 ->save();
		}
	}

	/**
	 * Parent node abandons children nodes, they become orphans of the next parent.
	 * 
	 * <code>
	 * 		$parent = Nestify::find(4);
	 * 
	 * 		// Abandon the children making them orphans to the parents parent.
	 * 		$parent->abandon();
	 * 
	 *		// Abandon the children then move the node to another position.
	 * 		$parent->abandon()->before(2);
	 * </code>
	 * 
	 * @return object
	 */
	public function abandon()
	{
		if(!$this->parent())
		{
			throw new Exception('Could not abandon children because the node is not a parent.');
		}

		// Shift all the children of the node forward one position.
		static::where('lft', 'BETWEEN', DB::raw(($this->lft + 1) . ' AND ' . ($this->rgt - 1)))->update(array(
			'lft' => DB::raw('lft + 1'),
			'rgt' => DB::raw('rgt + 1')
		));

		$this->rgt = $this->lft + 1;

		$this->save();

		return $this;
	}

	public function delete()
	{
		if($this->exists)
		{
			// If we are deleting then we'll allow Eloquent to take care of the rest.
			if($this->deleting)
			{
				parent::delete();

				$this->deleting = false;
			}

			// If not deleting yet we'll need to check what it is we are actually deleting.
			else
			{
				$this->deleting = true;

				// If the node is a parent then we are going to delete all the children along with it.
				// To delete a node without its children be sure to abandon the children before deleting.
				if($this->parent())
				{
					static::where('lft', 'BETWEEN', DB::raw($this->lft . ' AND ' . $this->rgt))->delete();
				}

				// If it's not a parent node then just delete the node, simple really!
				else
				{
					$this->deleting = false;

					// Allow Eloquent to delete the actual node.
					parent::delete();
				}

				// Now to re-adjust the tree removing the gap we have left.
				$this->adjustment($this->lft, ($this->width() + 1) * -1);
			}
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
	 * A node is a parent when its width is greater than one.
	 * 
	 * <code>
	 * 		if($node->parent())
	 * 		{
	 * 			// The node is a parent.
	 * 		}
	 * </code>
	 * 
	 * @return bool
	 */
	public function parent()
	{
		return $this->width() > 1;
	}

	/**
	 * Returns the width of the node in the tree.
	 * 
	 * @return int
	 */
	public function width()
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