<?php
	// initialize errors variable
	$host = 'nrb-term.mysql.database.azure.com';
	$username = 'nrbadmin@nrb-term';
	$password = 'M4W1srtA0l9';
	$db_name = 'term_proj';

// Create connection
	$conn = mysqli_init();
	mysqli_ssl_set($conn,NULL,NULL, "BaltimoreCyberTrustRoot.crt.pem", NULL, NULL) ; 
	mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306, MYSQLI_CLIENT_SSL);
	if (mysqli_connect_errno($conn)) {
		die('Failed to connect to MySQL: '.mysqli_connect_error());
	}	
?>