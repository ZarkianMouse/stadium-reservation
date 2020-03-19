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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
	<link rel="stylesheet" href="css/price.css">
	<link rel="stylesheet" href="css/seats.css">
	<link rel="stylesheet" href="css/abouts.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
		<br/>
		 <p>
			<a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
			<a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
		</p>
    </div>
   <div class="tabContainer">
		<div class="buttonContainer">
			<button onclick="showPanel(0,'grey')">View Price Tiers</button>
			<button onclick="showPanel(1,'grey')">View Available Seats</button>
			<button onclick="showPanel(2,'lightgrey')">Pagination Example</button>
		</div>
		<div class="tabPanel">
			<div class="content">
				<div class="edu">
				<?php include 'price-tiers.php' ?>
				</div>
			</div>
		</div>
		<div class="tabPanel">
			<div class="content">
				<div class="exp_body">
				<?php include 'seats.php' ?>
				</div>
			</div>
		</div>
		<div class="tabPanel">
			<?php include 'page-ex.php' ?>
		</div>
	<<script src="js/about.js" type="text/javascript"></script>
</body>
</html>