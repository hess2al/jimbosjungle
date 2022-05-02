<?php
session_start();

require_once "config.php";
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// 
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
	}
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err) ){
        // Prepare an update statement
        
        $sql = "UPDATE users SET password = $new_password WHERE username = $username";
        
        if($stmt = $pdo->prepare($sql)){
            
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":password", $param_new_password, PDO::PARAM_STR);
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_username = $_SESSION["username"];
			
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
				
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    // Close connection
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/loginstyle.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<html>

	<body>
	<div class="wrapper">
        <h1>Reset Password</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($first_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first; ?>">
                <span class="invalid-feedback"><?php echo $first_err; ?></span>
            </div>
            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($last_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last; ?>">
                <span class="invalid-feedback"><?php echo $last_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm New Password:</label>
                <input type="password" name="repeat-password" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
	</div>
    </body>
    
    
				
</html>