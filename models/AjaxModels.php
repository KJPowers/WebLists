<?php
// Lightweight models that will be serialized to JSON as responses to AJAX calls
include_once __DIR__.'/../controller/DB.php';
include_once 'Models.php';

// Response with the navbar items and the current list items
class AjaxNbAndCurrentItemsResponse
{
	public ?array $listItems = array();
	public ?array $nbItems   = array();

	public static function load(?string $listUuid):AjaxNbAndCurrentItemsResponse
	{
		$resp = new AjaxNbAndCurrentItemsResponse();

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

// Response with just the current list items
class AjaxCurrentItemsResponse
{
	public ?array $listItems = array();

	public static function load(?string $listUuid):AjaxCurrentItemsResponse
	{
		$resp = new AjaxCurrentItemsResponse();

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
