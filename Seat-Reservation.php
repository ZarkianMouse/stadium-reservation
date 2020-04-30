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

<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

$eventID = $_GET['EventID'];
$SeatID = $_GET['SeatID'];
$RowID = $_GET['RowID'];
$SectionID = $_GET['SectionID'];
$param_id = $_SESSION["id"];


if($seat_update = mysqli_query($conn,"INSERT INTO Reservations(UserID, EventID, SeatID, RowID, SectionID) VALUES ('$param_id', '$eventID', '$SeatID', '$RowID', '$SectionID')")){
    header("location: welcome.php");
    exit();
}
else{echo "No Work";}


?>