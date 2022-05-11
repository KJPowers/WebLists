<?php
class Index
{
	public $title;
	public $currentList;
	public $lists = array();
	public $items = array();
}

class WebList
{
	private ?string $uuid;
	public  ?string $name;
	public  ?string $description;
	public  ?array  $listItems = array();

	public function __construct(?string $uuid = null, ?string $name = null, ?string $description = null)
	{
		$this->uuid = $uuid;
		$this->name = $name;
		$this->description = $description;
	}

	public function uuid(): string
	{
		return $this->uuid;
	}

	public function hasItems():bool
	{
		return count($this->items) > 0;
	}

	public function toJSON():string
	{
		return json_encode($this);
	}
}

class NavbarWebList extends WebList
{
	public ?string $class = 'nav-link';

	public function __construct(?string $uuid = null, ?string $name = null, ?string $description = null)
	{
		parent::__construct($uuid, $name, $description);
	}
}

class Item
{
	public ?int    $id;
	public ?string $name;
	public ?string $description;

	public function __construct(?int $id = null, ?string $name = null, ?string $description = null)
	{
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
	}

/*	public function id():int
	{
		return $this->id;
	}*/
}

class NavbarItem extends Item
{
	public ?string $class = 'nav-link';

	public function __construct(?int $id = null, ?string $name = null, ?string $description = null)
	{
		parent::__construct($id, $name, $description);
	}
}

class CurrentListItem extends Item
{
	public ?string $class;

	public function __construct(?int $id = null, ?string $name = null, ?string $description = null, ?bool $marked = false)
	{
		parent::__construct($id, $name, $description);
		$this->class = $marked ? 'text-decoration-line-through' : '';
	}
}

class ListItem
{
	public $list_uuid;
	public $item_id;

	public function __construct(?string $uuid, ?int $id)
	{
		$this->list_uuid = $uuid;
		$this->item_id = $id;
	}
}
