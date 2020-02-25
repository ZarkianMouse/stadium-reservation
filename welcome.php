<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
else {
	 // initialize errors variable
	$host = 'nrb-term.mysql.database.azure.com';
	$db_username = 'nrbadmin@nrb-term';
	$password = 'M4W1srtA0l9';
	$db_name = 'term_proj';
	
// Create connection
	$conn = mysqli_init();
	mysqli_ssl_set($conn,NULL,NULL, "BaltimoreCyberTrustRoot.crt.pem", NULL, NULL) ; 
	mysqli_real_connect($conn, $host, $db_username, $password, $db_name, 3306, MYSQLI_CLIENT_SSL);
	if (mysqli_connect_errno($conn)) {
		die('Failed to connect to MySQL: '.mysqli_connect_error());
	}	

	// insert a quote if submit button is clicked
	if (isset($_POST['submit'])) {
		if (empty($_POST['habit'])) {
			$errors = "You must fill in the habit name field";
		}
		else if(empty($_POST['habit_t']))
		{
			$errors = "You must fill in the time duration field";
		}
		else if (empty($_POST['habit_f']))
		{
			$errors = "You must fill in the repetitions/day field";
		}
		else{
			$habit = $_POST['habit'];
			$habit_t = $_POST['habit_t'];
			$habit_f = $_POST['habit_f'];
			$user = $_SESSION["username"];
			$sql = "INSERT INTO habits (habitname,habittime,habitfreq,username) VALUES ('$habit',$habit_t,$habit_f,'$user')";
			echo $sql;
			mysqli_query($conn, $sql);
			header('location: welcome.php');
		}
	}	
	// delete task
if (isset($_GET['del_habit'])) {
	$id = $_GET['del_habit'];

	mysqli_query($conn, "DELETE FROM habits WHERE id=".$id);
	header('location: welcome.php');
}
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Welcome</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style.css">
		<style type="text/css">
			body{ font: 14px sans-serif; text-align: center; }
		</style>
	</head>
	<body>
		<h2>Welcome</h2>
	</body>

</html>