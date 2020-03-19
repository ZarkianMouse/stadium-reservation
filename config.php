<?php
	// initialize errors variable
	$db_host = 'remotemysql.com:3306';
	$db_user = 'ZVcWCWs7es';
	$db_pass = 'X9UGSF3Blr';
	$db_name = 'ZVcWCWs7es';

// Create connection
	$conn = mysqli_connect($db_host, $db_user, $db_pass,$db_name);
         
	 if(! $conn ) {
		die('Could not connect: ' . mysqli_error());
	 }
?>