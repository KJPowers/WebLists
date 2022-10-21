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
			DB::runQuery('INSERT INTO list_item ( list_uuid, item_id, sort_idx ) VALUES ( ?, ?, ?)', array($this->listUuid, $item_id, $sortIdx));
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
