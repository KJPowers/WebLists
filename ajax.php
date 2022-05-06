<?php

require_once 'controller/AjaxController.php';
require_once 'models/AjaxModels.php';

$params = parseAjaxParams();
$handler = null;
switch ($params['action']??null)
{
	case 'addItem':
		$handler = new AjaxAddItemHandler($params);
/*		if (Controller::doAddItem(new ListItem($params['listUuid'], $params['itemId'])))
		{
			header('Content-Type:·application/json');
			echo Controller::loadList($params)->toJSON();
		}*/
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

/*class Echo2
{
	public $list;
	public $uuid;
	public $id;
	public $foo;
	public $params = array();

	public function setIf($param)
	{
		if (isset($_POST[$param]))
		{
			$this->params[$param] = $_POST[$param];
			if ($param === 'list')
			{
				$this->list = $_POST[$param];
			}
			if ($param === 'uuid')
			{
				$this->uuid = $_POST[$param];
			}
			if ($param === 'id')
			{
				$this->id = $_POST[$param];
			}
		}
	}
}

$e = new Echo2();
$e->setIf('list');
$e->setIf('uuid');
$e->setIf('id');
//$e->foo='barbaz';
//$e->params['mine']='notyours';

header('Content-Type: application/json');
echo json_encode($e);*/
