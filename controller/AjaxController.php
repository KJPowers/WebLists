<?php
// Sunny-day handlers for AJAX requests. Throw errors when detected, and 'ajax.php' will handle them.

require_once 'DB.php';

// Parent class for all AJAX handlers
abstract class AjaxHandler
{
	protected ?string $action;

	public function __construct(?array $params)
	{
		$this->action = $params['action'];
	}

	abstract function handleAjax();

	abstract function validate();

	// Yay everything worked! Emit JSON output.
	function success($mdl)
	{
		header('Content-Type:·application/json');
		echo json_encode($mdl);
	}
}

// Handle ajax calls of type 'addItem'
class AjaxAddItemHandler extends AjaxHandler
{
	private ?string $listUuid;
	private ?int    $itemId;

	public function __construct(?array $params)
	{
		parent::__construct($params);
		$this->listUuid = $params['listUuid'];
		$this->itemId   = $params['itemId'];
	}

	function handleAjax()
	{
		$this->validate();

		$results = DB::runQuery('SELECT IFNULL(MAX(sort_idx)+1,0) "next" FROM list_item WHERE list_uuid=?', array($this->listUuid));
		$sortIdx = $results[0]['next'];
		DB::runQuery('INSERT INTO list_item ( list_uuid, item_id, sort_idx ) VALUES ( ?, ?, ? )', array($this->listUuid, $this->itemId, $sortIdx));

		$this->success(AjaxNbAndCurrentItemsResponse::load($this->listUuid));
	}

	function validate()
	{
		if ($this->action != 'addItem')
		{
			// TODO: throw an error
		}

		if (!isset($this->listUuid))
		{
			// TODO: throw an error
		}

		if (!isset($this->itemId))
		{
			// TODO: throw an error
		}

/*		if (TODO: check that user has permission to edit list and to access item)
		{
			// TODO: throw an error
		}*/
	}
}

// Handle ajax calls of type 'toggleMarked'
class AjaxToggleMarkedHandler extends AjaxHandler
{
	private ?string $listUuid;
	private ?int    $itemId;

	public function __construct(?array $params)
	{
		parent::__construct($params);
		$this->listUuid = $params['listUuid'];
		$this->itemId   = $params['itemId'];
	}

	function handleAjax()
	{
		$this->validate();

		DB::runQuery('UPDATE list_item SET marked = NOT marked WHERE list_uuid = ? AND item_id = ?', array($this->listUuid, $this->itemId));

		$this->success(AjaxCurrentItemsResponse::load($this->listUuid));
	}

	function validate()
	{
		if ($this->action != 'toggleMarked')
		{
			// TODO: throw an error
		}

		if (!isset($this->listUuid))
		{
			// TODO: throw an error
		}

		if (!isset($this->listUuid))
		{
			// TODO: throw an error
		}

/*		if (TODO: check that user has permission to edit list and to access item)
		{
			// TODO: throw an error
		}*/
	}
}

// Handle ajax calls of type 'clearMarked'
class AjaxClearMarkedHandler extends AjaxHandler
{
	private ?string $listUuid;

	public function __construct(?array $params)
	{
		parent::__construct($params);
		$this->listUuid = $params['listUuid'];
	}

	function handleAjax()
	{
		$this->validate();

		DB::runQuery('DELETE FROM list_item WHERE list_uuid = ? AND marked', array($this->listUuid));

		// Maintain sort_idx as a valid index
		// TODO: figure out how to do this non-iteratively
		$results = DB::runQuery('SELECT item_id, sort_idx, row_number() over () - 1 "idx" FROM list_item WHERE list_uuid = ? ORDER BY sort_idx', array($this->listUuid));
		foreach ($results as $row)
		{
			if ($row['sort_idx'] != $row['idx'])
			{
				DB::runQuery('UPDATE list_item SET sort_idx = ? WHERE list_uuid = ? AND item_id = ?', array($row['idx'], $this->listUuid, $row['item_id']));
			}
		}

		$this->success(AjaxNbAndCurrentItemsResponse::load($this->listUuid));
	}

	function validate()
	{
		if ($this->action != 'clearMarked')
		{
			// TODO: throw an error
		}

		if (!isset($this->listUuid))
		{
			// TODO: throw an error
		}

/*		if (TODO: check that user has permission to edit list and to access item)
		{
			// TODO: throw an error
		}*/
	}
}

// Handle ajax calls of type 'newItem'
class AjaxNewItemHandler extends AjaxHandler
{
	private ?string $listUuid;
	private ?string $itemName;
	private ?int    $itemName_len;

	public function __construct(?array $params)
	{
		parent::__construct($params);
		$this->listUuid = $params['listUuid'];
		$this->itemName = $params['itemName'];
		$this->itemName_len = $params['itemName_len'];
	}

	function handleAjax()
	{
		$this->validate();

		if ($this->itemName_len > 0)			// TEMPORARY!
		{																	// TEMPORARY!
			$results = DB::runQuery('SELECT * FROM item WHERE name = ?', array($this->itemName));
			$item_id;
			if (count($results) == 0)
			{
				DB::runQuery('INSERT INTO item (name) VALUES (?)', array($this->itemName));
				$results2 = DB::runQuery('SELECT * FROM item WHERE name = ?', array($this->itemName));
				$item_id = $results2[0]['id'];
			}
			else
			{
				$item_id = $results[0]['id'];
			}
			$results = DB::runQuery('SELECT IFNULL(MAX(sort_idx)+1,0) "next" FROM list_item WHERE list_uuid=?', array($this->listUuid));
			$sortIdx = $results[0]['next'];
			DB::runQuery('INSERT INTO list_item ( list_uuid, item_id, sort_idx ) VALUES ( ?, ?, ? )', array($this->listUuid, $item_id, $sortIdx));
		}																	// TEMPORARY!
		$this->success(AjaxNbAndCurrentItemsResponse::load($this->listUuid));
	}

	function validate()
	{
		if ($this->action != 'newItem')
		{
			// TODO: throw an error
		}

		if (!isset($this->itemName))
		{
			// TODO: throw an error
		}

		if (!isset($this->itemName_len))
		{
			// TODO: throw an error
		}

/*		if (TODO: check that user has permission to edit list and to access item)
		{
			// TODO: throw an error
		}*/

		if ($this->itemName_len == 0)
		{
			// TODO: silently fail
		}
	}
}

// Handle ajax calls of type 'updateSort'
class AjaxUpdateSortHandler extends AjaxHandler
{
	private ?string $listUuid;
	private ?int    $oldIdx;
	private ?int    $newIdx;
	private ?array  $results;

	public function __construct(?array $params)
	{
		parent::__construct($params);
		$this->listUuid = $params['listUuid'];
		$this->oldIdx   = $params['oldIdx'];
		$this->newIdx   = $params['newIdx'];
	}

	function handleAjax()
	{
		$this->results = DB::runQuery('SELECT item_id, sort_idx FROM list_item WHERE list_uuid=? ORDER BY sort_idx', array($this->listUuid));
		$this->validate();

		// This function transforms the table from state 0 to state 3 as follows for oldIdx=1 and newIdx=3
		// Each entry is in "val,sort_idx" form
		// 0)   1)   2)   3)
		// val,sort_idx
		//      b,-1 b,-1
		// a,0  a,0  a,0  a,0
		// b,1       c,1  c,1
		// c,2  c,2  d,2  d,2
		// d,3  d,3       b,3
		// e,4  e,4  e,4  e,4
		// f,5  f,5  f,5  f,5
		// g,6  g,6  g,6  g,6

		// State 0 -> state 1
		DB::runQuery('UPDATE list_item SET sort_idx = ? WHERE sort_idx = ?', array(-1, $this->oldIdx));

		// State 1 -> state 2
		if ($this->oldIdx < $this->newIdx)
		{
			DB::runQuery('UPDATE list_item SET sort_idx = sort_idx - 1 WHERE sort_idx BETWEEN ? AND ?', array($this->oldIdx, $this->newIdx));
		}
		else
		{
			DB::runQuery('UPDATE list_item SET sort_idx = sort_idx + 1 WHERE sort_idx BETWEEN ? AND ?', array($this->newIdx, $this->oldIdx));
		}

		// State 2-> state 3
		DB::runQuery('UPDATE list_item SET sort_idx = ? where sort_idx = ?', array($this->newIdx, -1));

		// Done!
		$this->success(AjaxCurrentItemsResponse::load($this->listUuid));
	}

	function validate()
	{
		if ($this->action != 'updateSort')
		{
			// TODO: throw an error
		}

		if (!isset($this->oldIdx))
		{
			// TODO: throw an error
		}

		if (!isset($this->newIdx))
		{
			// TODO: throw an error
		}

		if ($this->oldIdx < 0)
		{
			// TODO: throw an error
		}

		if ($this->newIdx < 0)
		{
			// TODO: throw an error
		}

		if ($this->oldIdx >= count($this->results) || $this->newIdx >= count($this->results))
		{
			// TODO: throw an error
		}

		if ($this->oldIdx != $this->results[$this->oldIdx]['sort_idx'] ||
		    $this->newIdx != $this->results[$this->newIdx]['sort_idx'])
		{
			// TODO: throw an error
		}

//		// TODO
//		if ($this->oldItemId != $this->results[$this->oldIdx]['item_id'] ||
//		    $this->newItemId != $this->results[$this->newIdx]['item_id')
//		{
//			// TODO: throw an error
//		}

/*		if (TODO: check that user has permission to edit list and to access item)
		{
			// TODO: throw an error
		}*/

		if ($this->oldIdx == $this->newIdx) // not possible?
		{
			// TODO: silently fail
		}
	}
}

// Handle ajax calls of type 'refresh'
class AjaxRefreshHandler extends AjaxHandler
{
	private ?string $listUuid;

	public function __construct(?array $params)
	{
		parent::__construct($params);
		$this->listUuid = $params['listUuid'];
	}

	function handleAjax()
	{
		// Dead simple, just reload everything
		$this->success(AjaxNbAndCurrentItemsResponse::load($this->listUuid));
	}

	function validate()
	{
		if ($this->action != 'refresh')
		{
			// TODO: throw an error
		}

		if (!isset($this->listUuid))
		{
			// TODO: throw an error
		}

/*		if (TODO: check that user has permission to edit list and to access item)
		{
			// TODO: throw an error
		}*/
	}
}

