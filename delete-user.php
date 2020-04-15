<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$confirm_password = "";$confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
	
		
	
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please enter a password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
		$sql = "SELECT Password FROM Users WHERE UserID = ?";
		if($stmt = mysqli_prepare($conn, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "i", $param_id);
			$param_id = $_SESSION["id"];
			if(mysqli_stmt_execute($stmt)){
				mysqli_stmt_store_result($stmt);
				// Check if username exists, if yes then verify password
				 if(mysqli_stmt_num_rows($stmt) == 1){                    
					// Bind result variables
					mysqli_stmt_bind_result($stmt, $hashed_password);
					if(mysqli_stmt_fetch($stmt)){
						if(password_verify($confirm_password, $hashed_password) == false){
							$confirm_password_err = "Password did not match.";
						}
					}
				 }
			}
		}

    }
        
    // Check input errors before updating the database
    if(empty($confirm_password_err)){
		
		
		
		
        // Prepare an update statement
        $sql = "DELETE FROM Reservations WHERE UserID = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Seats updated successfully. Destroy the session, and redirect to login page
				echo "successfully remove seats";
				// Prepare a delete statement
				$sql = "DELETE FROM Users WHERE UserID = ?";
				
				if($delete_stmt = mysqli_prepare($conn, $sql)){
					// Bind variables to the prepared statement as parameters
					mysqli_stmt_bind_param($delete_stmt, "i", $param_id);
					
					// Set parameters
					$param_id = $_SESSION["id"];
					
					// Attempt to execute the prepared statement
					if(mysqli_stmt_execute($delete_stmt)){
						header("location: logout.php");
						exit();
					}
					else {
						echo "Failure";
					}
					mysqli_stmt_close($stmt);
				}
			} else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deleting Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Delete Account</h2>
        <p>Please enter your password to delete your account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-conn" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>