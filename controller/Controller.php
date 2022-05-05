<?php

class Controller
{
	const UUID_REGEX = "/^[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}\$/i";

	// Load a list by name, uuid, user, etc
	public static function loadList($params): WebList
	{
		$query = null;
		$qparams = array();
		if (array_key_exists('listUuid', $params))
		{
			$query = 'SELECT list.uuid, list.name lname, list.description ldesc, item.id, item.name iname, item.description idesc FROM list LEFT JOIN list_item ON list.uuid = list_item.list_uuid LEFT JOIN item ON list_item.item_id = item.id WHERE list.uuid = ? ORDER BY list_item.sort_idx';
			$qparams[] = $params['listUuid'];
		}
		else
		{
			echo "NOT FOUND";
			return null;
		}

		$results = Controller::runQuery($query, $qparams);
		// Sanity check
		if (count($results) === 0)
		{
			return null;
		}

		$wl = new WebList($results[0]['uuid'], $results[0]['lname'], $results[0]['ldesc']);

		foreach ($results as $row)
		{
			$wl->items[] = new Item($row['id'], $row['iname'], $row['idesc']);
		}

		return $wl;
	}

	// This is the method called by ajax.php
	public static function doAjax()
	{
		$params = Controller::parseAjaxParams();
		switch ($params['action'])
		{
			case 'addItem':
				if (Controller::doAddItem(new ListItem($params['listUuid'], $params['itemId'])))
				{
					header('Content-Type:·application/json');
					echo Controller::loadList($params)->toJSON();
				}
				break;
			default:
				echo 'Error:·unknown·action';
		}
	}

	// Parse the params in an ajax request, sanitizing them along the way
	public static function parseAjaxParams(): array
	{
		$params = array();

		// action can be one of: addItem
		$param = 'action';
		switch ($_POST[$param])
		{
			case 'addItem':
				$params[$param] = $_POST[$param];
		}

		// itemId must be numeric
		$param = 'itemId';
		if (is_numeric($_POST[$param]))
		{
			$params[$param] = (int)$_POST[$param];
		}

		// listUuid must be a UUID
		$param = 'listUuid';
//		if (preg_match(Controller::UUID_REGEX, $_POST[$param]))
		if (preg_match('/^[0-9a-z]+$/i', $_POST[$param]))	// TODO: properly handle this
		{
			$params[$param] = $_POST[$param];
		}

		// list must be alphanumeric
/*		$param = 'list';
		if (preg_match('/^[0-9a-z]+$/i', $_POST[$param]))
		{
			$params[$param] = $_POST[$param];
		}*/

		return $params;
	}

	// *DEPRECATED* This handles a POST request to add an item
	public static function handleFormData()
	{
		if (isset($_POST['inAddItem']))
		{
//			echo "addThisItem";
			Controller::doAddItem(new ListItem($_GET['list'], $_POST['inAddItem']));
		}
	}

	// Create a new LIST_ITEM row. assumes validated input
	public static function doAddItem(ListItem $listItem): bool
	{
		Controller::runQuery('INSERT INTO list_item ( list_uuid, item_id ) VALUES ( ?, ? )', array($listItem->list_uuid, $listItem->item_id));
		return true;
	}

	public static function loadModel($uuid):Index
	{
		global $WEBLISTS_title;
		$mdl = new Index();

		// Set some basic things
		$mdl->title = $WEBLISTS_title;

		// Get all lists
		// TODO: for the current user
		$results = Controller::runQuery('SELECT * FROM list ORDER BY name');
		foreach ($results as $row)
		{
			$nwl = new NavbarWebList($row['uuid'], $row['name'], $row['description']);
			if ($uuid === $nwl->uuid())
			{
				$mdl->currentList = $nwl;
			}
			else
			{
				$mdl->lists[] = $nwl;
			}
		}

		// Get all items
		// TODO: for the current user
		$results = Controller::runQuery('SELECT item.*, list_item.list_uuid FROM item LEFT JOIN list_item ON item.id = list_item.item_id ORDER BY sort_idx, name');
		foreach ($results as $row)
		{
			$ni = new NavbarItem($row['id'], $row['name'], $row['description']);
			if (isset($uuid) && $uuid === $row['list_uuid'])
			{
				$mdl->currentList->items[] = $ni;
			}
			else
			{
				$mdl->items[] = $ni;
			}
		}

		return $mdl;
	}

	public static function runQuery($query, $params=null)
	{
		global $WEBLISTS_db_dsn;
		global $WEBLISTS_db_user;
		global $WEBLISTS_db_pass;

		$pdo = null;
		$pdos = null;
		try
		{
			// Open a connection to the DB
			$pdo = new PDO($WEBLISTS_db_dsn, $WEBLISTS_db_user, $WEBLISTS_db_pass);

			// Prepare and run the query
			$pdos = $pdo->prepare($query);
			$pdos->execute($params);
			$rows = $pdos->fetchAll();

			return $rows;
		}
		catch (PDOException $e)
		{
			// TODO: make this better
			print "DB error in runQuery(): " . $e->getMessage() . "<br/>";
			die();
		}
		finally
		{
			$pdos = null;
			$pdo = null;
		}
	}
}
