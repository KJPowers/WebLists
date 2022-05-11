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

	// action can be one of: addItem, editItem (TODO), removeItem (TODO), addList (TODO), editList(TODO), removeList(TODO)
	$param = 'action';

	switch ($_POST[$param]??null)
	{
		case 'addItem':
		case 'toggleMarked':
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
		$params[$param] = $_POST[$param];
	}

	// list must be alphanumeric
/*	$param = 'list';
	if (preg_match('/^[0-9a-z]+$/i', $_POST[$param]))
	{
		$params[$param] = $_POST[$param];
	}*/

	return $params;
}
