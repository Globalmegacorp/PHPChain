<?php
// database.php -- MySQL database access functions
// This file contains the MySQL password - CAREFUL!
	
// Configuration settings
$sql_vars = array(
	'host' => "localhost",			// SQL server host
	'username' => "",			// Username
	'password' => "",			// Password
	'dbname' => "phpchain",			// Database name
);
	
// Connect to the MySQL server
function sql_conn()
{
	global $sql_vars;

	// Open a connection. Can change to mysql_pconnect if it will
	// give you any advantage. Read here for info on this:
	// http://www.php.net/manual/en/features.persistent-connections.php

	$db = mysqli_connect($sql_vars["host"], $sql_vars["username"], $sql_vars["password"], $sql_vars["dbname"]);
	return $db;
}

function restoarray($resdata)
{
	$n=0;
	while ($row=mysqli_fetch_row($resdata)) {
		$data[$n]=$row;
		$n++;
	}
	return $data;
}


