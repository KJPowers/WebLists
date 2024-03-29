<?php

class Controller
{
	const UUID_REGEX = "/^[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}\$/i";

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
		$results = Controller::runQuery('SELECT item.*, list_item.list_uuid, list_item.marked FROM item LEFT JOIN list_item ON item.id = list_item.item_id AND list_item.list_uuid = ? ORDER BY sort_idx, name', array($uuid));
		foreach ($results as $row)
		{
			$ni = new NavbarItem($row['id'], $row['name'], $row['description'], $row['marked']);
			if (isset($uuid) && $uuid === $row['list_uuid'])
			{
				$mdl->currentList->listItems[] = new CurrentListItem($row['id'], $row['name'], $row['description'], $row['marked']);
			}
			else
			{
				$mdl->items[] = new NavbarItem($row['id'], $row['name'], $row['description']);
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
