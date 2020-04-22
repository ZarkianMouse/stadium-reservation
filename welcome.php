<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
else {
	require_once "config.php";
	$href="?";
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/abouts.css">

</head>
<body>
     <div class="page-header site_head">
        <p class="site_name">Welcome to <em>Stadium Reservations</em></p>
		<div class="site_nav">
			<h3>
				Good day, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>!
			</h3>
			<p>
				<a href="logout.php" class="btn btn-default my_primary">Sign Out of Your Account</a>
				<a href="delete-user.php" class="btn btn-default my_delete">Delete Your Account</a>
				<a href="reset-password.php" class="btn btn-default my_primary">Reset Your Password</a>
			</p>
		</div>
    </div>
   <div class="tabContainer">
		<div class="buttonContainer">
			<button onclick="showPanel(0,'prices')">View Events</button>
			<button onclick="showPanel(1,'seats')">View Reservations</button>
		</div>
		<div  class="tabPanel" id="prices" >
			<div class="content">
				<?php if(isset($_GET['EventID']) and $_GET['EventID'] != "")
						{
							include 'section-finder.php';
						}
					  else {
						  include 'event-selector.php';
					  }
					?>

			</div>
			
		</div>
		<div class="tabPanel" id="seats" >
			<div class="content">
				<?php include 'Reservation-Finder.php' ?>
			</div>
		</div>
		
	</div>
	
	
	<script src="js/about.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"> 
	</script> 
	<script src= 
 	"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"> 
	</script>
</body>
</html>