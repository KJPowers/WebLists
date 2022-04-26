<?php

class Controller
{
  public static function loadModel($uuid)
  {
    $mdl = new Index();

    // We define these in config.php
    global $WEBLISTS_db_dsn;
    global $WEBLISTS_db_user;
    global $WEBLISTS_db_pass;

    // Open a connection to the DB
    // TODO: move this to a helper method
    try
    {
      $pdo = new PDO($WEBLISTS_db_dsn, $WEBLISTS_db_user, $WEBLISTS_db_pass);
    }
    catch (PDOException $e)
    {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }

    if (isset($uuid))
    {
      // Query the DB
      $sth = $pdo->prepare('SELECT * FROM list WHERE uuid=?');
      $sth->execute(array($uuid));
      $foo = $sth->fetchAll();

      if (count($foo) === 0)
      {
        // No list found, or no list selected
        $mdl->currentListName = '<select a list>';
      }
      elseif (count($foo) === 1)
      {
        // Found the list
        $mdl->currentListName = $foo[0]['name'];
      }
      else
      {
        // multiple lists found. should be impossible because of the unique constraint in the DB
        echo "oh no no no no no";
      }

      // Close the query
      $sth = null;
    }
    else
    {
      // no list specified
      echo "not specified";
    }

    // Close the DB connection
    $pdo = null;

    // Build the model to return
//    $mdl->currentListName = '<Current List Name>';
    $mdl->lists = array(
      new NavbarWebList('abc', '<List 1>', 'The first list'),
      new NavbarWebList('def', '<List 2>', 'The second list'),
      new NavbarWebList('ghi', '<List 3>', 'The third list'),
    );
    $mdl->items = array(
      new NavbarItem(1, '<Item 1>', 'foo'),
      new NavbarItem(2, '<Item 2>', ''),
      new NavbarItem(3, '<Item 3>', ''),
    );

    return $mdl;
  }
}
