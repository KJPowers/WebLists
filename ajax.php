<?php
ini_set('display_errors', 1);

require_once 'config.php';
require_once 'models/Models.php';
require_once 'controller/Controller.php';

//echo "foobar";

Controller::doAjax();
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
