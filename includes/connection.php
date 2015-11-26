<?php

//DB configuration Constants
	define('_HOST_NAME_', 'localhost');
	define('_USER_NAME_', 'example_user');
	define('_DB_PASSWORD', 'example_password');
	define('_DATABASE_NAME_', 'example_database');
	
	//PDO Database Connection
	try {
		$db = new PDO('mysql:host='._HOST_NAME_.';dbname='._DATABASE_NAME_, _USER_NAME_, _DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
		echo 'ERROR: ' . $e->getMessage();
	}

?>