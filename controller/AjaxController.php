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

		DB::runQuery('INSERT INTO list_item ( list_uuid, item_id ) VALUES ( ?, ? )', array($this->listUuid, $this->itemId));

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
