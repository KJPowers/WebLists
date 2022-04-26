<?php
class Index
{
  public $lists;
  public $items;
  public $currentListName;
}

class WebList
{
  private ?string $uuid;
  public  ?string $name;
  public  ?string $description;

  public function __construct(?string $uuid = null, ?string $name = null, ?string $description = null)
  {
    $this->uuid = $uuid;
    $this->name = $name;
    $this->description = $description;
  }

  public function uuid(): string
  {
    return $this->$uuid;
  }
}

class NavbarWebList extends WebList
{
  public ?string $class = 'nav-link';

  public function __construct(?string $uuid = null, ?string $name = null, ?string $description = null)
  {
    parent::__construct($uuid, $name, $description);
  }

  public function id(): int
  {
    return $this->$id;
  }
}

class Item
{
  private ?int    $id;
  public ?string $name;
  public ?string $description;

  public function __construct(?int $id = null, ?string $name = null, ?string $description = null)
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
  }
}

class NavbarItem extends Item
{
  public ?string $class = 'nav-link';

  public function __construct(?int $id = null, ?string $name = null, ?string $description = null)
  {
    parent::__construct($id, $name, $description);
  }
}

