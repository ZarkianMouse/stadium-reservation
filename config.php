<?php
	// initialize errors variable
	$host = 'remotemysql.com:3306';
	$username = 'ZVcWCWs7es';
	$password = 'X9UGSF3Blr';
	$db_name = 'ZVcWCWs7es';

// Create connection
	$conn = mysqli_connect($host, $username, $password);
         
         if(! $conn ) {
            die('Could not connect: ' . mysqli_error());
         }
         echo 'Connected successfully';
         mysqli_close($conn);
?>