<?php
	$servername = 'localhost';
	$username = '';
	$password = '';
	$database = 'internlite';

	// Create connection
	$db = mysqli_connect($servername, $username, $password, $database);

	// Check connection
	if (!$db) {
		array_push($errors, "Database Error: ".mysqli_connect_error());
	}
?>
