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
			'SELECT item.*, list_item.list_uuid ' .
			'FROM item ' .
			  'LEFT JOIN list_item ON item.id = list_item.item_id AND list_item.list_uuid = ? ' .
			'ORDER BY sort_idx, name',
			array($listUuid));
		foreach ($results as $row)
		{
			if ($listUuid === $row['list_uuid'])
			{
				$resp->listItems[] = new NavbarItem($row['id'], $row['name'], $row['description']);
			}
			else
			{
				$resp->nbItems[] = new NavbarItem($row['id'], $row['name'], $row['description']);
			}
		}

		return $resp;
	}
}
