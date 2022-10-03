<?php

require_once 'controller/AjaxController.php';
require_once 'models/AjaxModels.php';

$params = parseAjaxParams();
$handler = null;
switch ($params['action']??null)
{
	case 'addItem':
		$handler = new AjaxAddItemHandler($params);
		break;
	case 'toggleMarked':
		$handler = new AjaxToggleMarkedHandler($params);
		break;
	case 'clearMarked':
		$handler = new AjaxClearMarkedHandler($params);
		break;
	case 'newItem':
		$handler = new AjaxNewItemHandler($params);
		break;
	default:
//		$handler = new AjaxErrorHandler($params);
		echo 'Error:·unknown·action';
		die();
}

try
{
	$handler->handleAjax();
}
catch (Exception $e)
{
	// TODO
}

exit;

// Parse the params in an ajax request, sanitizing them along the way
function parseAjaxParams(): array
{
	$params = array();

	// action can be one of: addItem, toggleMarked, clearMarked, newItem, editItem (TODO), removeItem (TODO), addList (TODO), editList(TODO), removeList(TODO), newList(TODO)
	$param = 'action';

	switch ($_POST[$param]??null)
	{
		case 'addItem':
		case 'toggleMarked':
		case 'clearMarked':
		case 'newItem':
			$params[$param] = $_POST[$param];
	}

	// itemId must be numeric
	$param = 'itemId';
	if (is_numeric($_POST[$param]??null))
	{
		$params[$param] = (int)$_POST[$param];
	}

	// listUuid must be a UUID
	$param = 'listUuid';
//	if (preg_match(Controller::UUID_REGEX, $_POST[$param]))
	if (preg_match('/^[0-9a-z]+$/i', $_POST[$param]??'')) // TODO: listUuid must be a UUID
	{
		$params[$param] = $_POST[$param]??'';
	}

	// itemName can be at most 250 characters long.
	// itemName_len goes along for the ride so we can warn the user.
	$param = 'itemName';
	$params[$param]         = mb_substr($_POST[$param]??'', 0, 250);
	$params['itemName_len'] = mb_strlen($_POST[$param]??'');

	// list must be alphanumeric
/*	$param = 'list';
	if (preg_match('/^[0-9a-z]+$/i', $_POST[$param]))
	{
		$params[$param] = $_POST[$param];
	}*/

	return $params;
}
