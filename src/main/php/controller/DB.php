<?php
require_once __DIR__.'/../config.php';

class DB
{
	public static function runQuery($query, $params=null):array
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
