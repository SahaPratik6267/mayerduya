<?php

	/**
	* Configuration file
	*
	* This should be the only file you need to edit in, regarding the original script.
	* Please provide your MySQL login information below.
	*/

	$GLOBALS["mysql_hostname"] = "localhost";
	$GLOBALS["mysql_username"] = "mayerduya_newdb";
	$GLOBALS["mysql_password"] = "dua123!@#";
	$GLOBALS["mysql_database"] = "mayerduya_db2";
	
$databaseHost = 'localhost';
$databaseName = 'mayerduya_db2';
$databaseUsername = 'mayerduya_newdb';
$databasePassword = 'dua123!@#';

$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName); 
?>