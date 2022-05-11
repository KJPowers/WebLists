<?php
// Lightweight models that will be serialized to JSON as responses to AJAX calls
include_once __DIR__.'/../controller/DB.php';
include_once 'Models.php';

// Response to 'addItem'
class AjaxAddItemResponse
{
	public ?array $listItems = array();
	public ?array $nbItems   = array();

	public static function load(?string $listUuid):AjaxAddItemResponse
	{
		$resp = new AjaxAddItemResponse();

		$results = DB::runQuery(
			'SELECT item.*, list_item.list_uuid, list_item.marked ' .
			'FROM item ' .
			  'LEFT JOIN list_item ON item.id = list_item.item_id AND list_item.list_uuid = ? ' .
			'ORDER BY sort_idx, name',
			array($listUuid));
		foreach ($results as $row)
		{
			if ($listUuid === $row['list_uuid'])
			{
				$resp->listItems[] = new CurrentListItem($row['id'], $row['name'], $row['description'], $row['marked']);
			}
			else
			{
				$resp->nbItems[] = new NavbarItem($row['id'], $row['name'], $row['description']);
			}
		}

		return $resp;
	}
}

// Response to 'toggleMarked'
class AjaxToggleMarkedResponse
{
	public ?array $listItems = array();

	public static function load(?string $listUuid):AjaxToggleMarkedResponse
	{
		$resp = new AjaxToggleMarkedResponse();

		$results = DB::runQuery(
			'SELECT item.*, list_item.list_uuid, list_item.marked ' .
			'FROM item ' .
			  'JOIN list_item ON item.id = list_item.item_id AND list_item.list_uuid = ? ' .
			'ORDER BY sort_idx, name',
			array($listUuid));
		foreach ($results as $row)
		{
			$resp->listItems[] = new CurrentListItem($row['id'], $row['name'], $row['description'], $row['marked']);
		}

		return $resp;
	}
}
