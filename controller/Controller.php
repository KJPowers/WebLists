<?php

class Controller
{
  public static function loadModel($uuid)
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
    $results = Controller::runQuery('SELECT item.*, list_item.list_uuid FROM item LEFT JOIN list_item ON item.id = list_item.item_id ORDER BY (CASE WHEN list_uuid IS null THEN 1 ELSE 0 END), sort_idx');
    foreach ($results as $row)
    {
      $ni = new NavbarItem($row['id'], $row['name'], $row['description']);
      if (isset($uuid) && $uuid === $row['list_uuid'])
      {
        $mdl->currentList->items[] = $ni;
      }
      else
      {
        $mdl->items[] = $ni;
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
